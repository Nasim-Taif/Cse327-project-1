<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include DB and functions only once
include_once "db.php";
include_once "admin_functions.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $admin = adminLogin($conn, $email, $password);

    if ($admin) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: admindashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - TurfMate</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; margin: 0; }
        .login-container { width: 400px; background: white; padding: 25px; margin: 50px auto; border-radius: 10px; box-shadow: 0px 0px 10px #bbb; }
        input { width: 100%; padding: 12px; margin-top: 10px; border-radius: 6px; border: 1px solid #888; }
        button { width: 100%; padding: 12px; margin-top: 15px; border: none; background: #1a73e8; color: white; font-size: 16px; border-radius: 6px; cursor: pointer; }
        .error { color: red; margin-top: 10px; }
        nav { background: #222; color: white; padding: 15px; display: flex; justify-content: space-between; }
        .nav-links a { color: #fff; margin-left: 15px; text-decoration: none; }
    </style>
</head>
<body>

<nav class="nav-bar">
    <div class="logo">TurfMate</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="#" id="aboutBtn">About TurfMate</a>
        <a href="login.php" class="login-btn">Login</a>
        <a href="adminlogin.php" class="login-btn">Login as admin</a>
    </div>
</nav>

<div class="login-container">
    <h2>Admin Login</h2>

    <?php if ($error != ""): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

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
