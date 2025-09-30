<?php
session_start();
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Display errors on the screen
include("connection/connect.php");
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Validate inputs
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $address = trim($_POST['address']);

    if (empty($firstname) || empty($lastname) || empty($email) || 
        empty($phone) || empty($username) || empty($password) || 
        empty($cpassword) || empty($address)) {
        $message = "All fields must be required!";
    } elseif ($password !== $cpassword) {
        $message = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long!";
    } elseif (strlen($phone) < 10) {
        $message = "Invalid phone number!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address!";
    } else {
        // Prepared statement for username and email checks
        $stmt = $db->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $message = "Username or email already exists!";
        } else {
            // Password hashing
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Insert new user
            $insertStmt = $db->prepare("INSERT INTO users (username, f_name, l_name, email, phone, password, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("sssssss", $username, $firstname, $lastname, $email, $phone, $hashedPassword, $address);
            if ($insertStmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Error registering user. Please try again.";
            }
            
            $insertStmt->close();
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div style="background-image: url('images/img/pimg.jpg');">
        <header class="header-scroll top-header">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="index.php"><img src="images/icn.png" alt=""></a>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link active" href="restaurants.php">Restaurants</a></li>
                            <?php if (empty($_SESSION["user_id"])): ?>
                                <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
                                <li class="nav-item"><a class="nav-link active" href="registration.php">Register</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link active" href="your_orders.php">My Orders</a></li>
                                <li class="nav-item"><a class="nav-link active" href="logout.php">Logout</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="page-wrapper">
            <section class="contact-page inner-page">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="widget">
                                <div class="widget-body">
                                    <?php if (!empty($message)): ?>
                                        <div class="alert alert-danger"><?php echo $message; ?></div>
                                    <?php endif; ?>
                                    <form id="registrationForm" action="" method="post">
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label for="username">User-Name</label>
                                                <input class="form-control" type="text" name="username" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="firstname">First Name</label>
                                                <input class="form-control" type="text" name="firstname" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="lastname">Last Name</label>
                                                <input class="form-control" type="text" name="lastname" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="email">Email Address</label>
                                                <input type="email" class="form-control" name="email" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="phone">Phone number</label>
                                                <input class="form-control" type="text" name="phone" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" name="password" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cpassword">Confirm Password</label>
                                                <input type="password" class="form-control" name="cpassword" required>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label for="address">Delivery Address</label>
                                                <textarea class="form-control" name="address" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="submit" value="Register" name="submit" class="btn theme-btn">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="footer">
                <div class="container">
                    <div class="row bottom-footer">
                        <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>Payment Options</h5>
                            <ul>
                                <li><a href="#"><img src="images/paypal.png" alt="Paypal"></a></li>
                                <li><a href="#"><img src="images/mastercard.png" alt="Mastercard"></a></li>
                                <li><a href="#"><img src="images/maestro.png" alt="Maestro"></a></li>
                                <li><a href="#"><img src="images/stripe.png" alt="Stripe"></a></li>
                                <li><a href="#"><img src="images/bitcoin.png" alt="Bitcoin"></a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 address color-gray">
                            <h5>Address</h5>
                            <p>1086 Stockert Hollow Road, Seattle</p>
                            <h5>Phone: 75696969855</h5>
                        </div>
                        <div class="col-xs-12 col-sm-5 additional-info color-gray">
                            <h5>Additional Information</h5>
                            <p>Join thousands of other restaurants who benefit from having partnered with us.</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- <script>
$(document).ready(function () {
    $('#registrationForm').on('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        // Gather form data
        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '', // Ensure this URL points to your PHP script
            data: formData,
            success: function (response) {
                // Log the response to check what's being returned
                console.log(response);
               //  alert(response); // Show the server response message
                
                // Optionally parse JSON if you're sending a JSON response
                // var res = JSON.parse(response);
                // if (res.status === 'success') {
                    window.location.href = 'login.php'; // Redirect to login page
                // }
            },
            error: function (xhr, status, error) {
                // Log error information for debugging
                console.error("Error:", error);
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script> -->

    </body>
</html>
