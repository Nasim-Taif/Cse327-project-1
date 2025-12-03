<?php
/**
 * Homepage
 * Displays main introduction, gallery, and Book Now button.
 *
 * @package Turfmate
 */
session_start();

// If user already logged in, go straight to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: select_game.php");
    exit;
}

require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']); // plain text

    // Get user by email
    $stmt = $conn->prepare("SELECT customer_id, full_name, password FROM customers WHERE email = ?");
    if (!$stmt) {
        die("Query failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($customer_id, $full_name, $db_password);

    if ($stmt->fetch()) {
        // PLAIN PASSWORD CHECK (NO HASH)
        if ($password === $db_password) {
            // Login success
            $_SESSION['user_id']   = $customer_id;
            $_SESSION['full_name'] = $full_name;

            $stmt->close();
            header("Location: select_game.php");
            exit;
        } else {
            $error = "Wrong password.";
        }
    } else {
        $error = "Email not found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Login - Turfmate</title>
</head>
<body>
<h2>Customer Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Not registered yet? <a href="register.php">Register here</a></p>
</body>
</html>
