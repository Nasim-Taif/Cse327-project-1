<?php
/**
 * Checkout Page
 * Shows selected game, date, and slot before payment.
 *
 * @package Turfmate
 */
session_start();
include "db.php";
include "includes/header.php";
?>

<h2 class="form-title">Login</h2>

<form method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="login">Login</button>

    <p>Not registered yet? <a href="register.php">Register here</a></p>
</form>

<?php
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT customer_id, password FROM customers WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($customer_id, $hashedPassword);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $customer_id;
            header("Location: dashboard.php");
            exit();
        }
    }

    echo "<p class='error'>Invalid email or password.</p>";
}
?>

</body>
</html>
