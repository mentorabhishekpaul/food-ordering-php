<!DOCTYPE html>
<html lang="en" >
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  echo "<script>console.log('Username: " . $username . "');</script>";
  echo "<script>console.log('Password: " . $password . "');</script>";

  $loginquery = "SELECT * FROM admin WHERE username='$username'";
  $result = mysqli_query($db, $loginquery);
  $row = mysqli_fetch_array($result);

  if (is_array($row)) {
      // Log the stored password hash for comparison
      echo "<script>console.log('Stored Password Hash: " . $row['password'] . "');</script>";
      // echo password_hash('newPassword123', PASSWORD_BCRYPT);
      echo "<script>console.log('Password Verify Result: " . ($isPasswordValid ? 'true' : 'false') . "');</script>";

      // Manually hash the entered password and compare it
      $hashed_password = $row['password'];
      $input_password = $password;

      // This will compare the input password with the hash and log the result
      $isPasswordValid = password_verify($input_password, $hashed_password);
      echo "<script>console.log('Password Verify Result: " . ($isPasswordValid ? 'true' : 'false') . "');</script>";

      if ($isPasswordValid) {
          $_SESSION["adm_id"] = $row['adm_id'];
          header("refresh:1;url=dashboard.php");
      } else {
          echo "<script>alert('Invalid Username or Password!');</script>";
      }
  } else {
      echo "<script>alert('Invalid Username or Password!');</script>";
  }
}

?>


<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

      <link rel="stylesheet" href="css/login.css">

  
</head>

<body>

  
<div class="container">
  <div class="info">
    <h1>Admin Panel </h1>
  </div>
</div>
<div class="form">
  <div class="thumbnail"><img src="images/manager.png"/></div>
  <span style="color:red;"><?php echo $message; ?></span>
   <span style="color:green;"><?php echo $success; ?></span>
  <form class="login-form" action="index.php" method="post">
    <input type="text" placeholder="Username" name="username"/>
    <input type="password" placeholder="Password" name="password"/>
    <input type="submit"  name="submit" value="Login" />

  </form>
  
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='js/index.js'></script>
</body>

</html>
