# Online Food Ordering Admin Panel (PHP + MySQL)

A simple PHP web application for managing **restaurants, categories, dishes, users, and orders** with an admin dashboard.  
Built using **PHP, MySQL, Bootstrap, and jQuery**, it provides CRUD operations, image uploads, and an admin login system.

---

## 🚀 Features
- Manage **categories** (add, edit, delete)  
- Manage **restaurants** with image uploads  
- Manage **dishes/menu items** with price & image  
- View and manage **users**  
- View and update **orders**  
- Dashboard showing counts & total earnings  

---

## 🛠️ Tech Stack
- PHP (procedural)
- MySQL / MariaDB
- Bootstrap + jQuery
- DataTables (for tables)

---

## 📂 Project Setup
1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd <your-repo>
2. **Create a database in MySQL**
   CREATE DATABASE onlinefoodphp;
3. **Import the tables**
Tables required: res_category, restaurant, dishes, users, users_orders, admin.
4. **Configure the connection in connection/connect.php**
   $servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "onlinefoodphp";
5. **Run locally**
Place the project in your server root (htdocs/ or www/) and open:
http://localhost/<your-folder>/admin

---
## 🔐 Admin Login
Admin credentials are stored in the admin table.
Passwords should be stored using password_hash() (bcrypt).
Authentication uses password_verify() for login.

---
## ⚠️ Security Notes
Current code uses raw SQL → vulnerable to SQL Injection.
File upload validation is minimal (check size & MIME type before saving).
Error messages are displayed — disable in production.
Add CSRF tokens and secure session handling before deployment.
