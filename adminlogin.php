<?php
session_start();
include "db.php"; 

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch admin from DB
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check admin
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Password check (plain text for now since your DB has plain password)
        if ($password === $admin['password']) {

            // Set session
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: admindashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Turfmate</title>
    <link rel="stylesheet" href="style.css"> <!-- optional -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
        }
        .login-container {
            width: 400px;
            background: white;
            padding: 25px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #bbb;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #888;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            background: #1a73e8;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .error { color: red; margin-top: 10px; }
        nav {
            background: #222;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
        }
        .nav-links a {
            color: #fff;
            margin-left: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- NAVIGATION BAR -->
<nav class="nav-bar">
    <div class="logo">Turfmate</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="#" id="aboutBtn">About Turfmate</a>
        <a href="login.php" class="login-btn">Login</a>
        <a href="adminlogin.php" class="login-btn">Login as admin</a>
    </div>
</nav>

<div class="login-container">
    <h2>Admin Login</h2>

    <?php if ($error != "") { echo "<p class='error'>$error</p>"; } ?>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
