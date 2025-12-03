<?php
/**
 * Customer Registration Script
 *
 * This script handles the registration of new customers for Turfmate.
 * It validates input, checks if the email already exists, then inserts
 * the new customer record into the `customers` table. On success, it
 * starts a session for the user and redirects to the game selection page.
 *
 * PHP version 8+
 *
 * @category  Turfmate
 * @package   TurfmateBookingSystem
 */

session_start();
require 'db.php';

/**
 * Holds an error message to display to the user if registration fails.
 *
 * @var string
 */
$error = "";

/**
 * Registers a new customer in the database.
 *
 * This function:
 * - Checks if the email already exists.
 * - Inserts a new record into the `customers` table if email is unique.
 * - Sets session variables on success.
 *
 * @param mysqli $conn      Active MySQLi connection.
 * @param string $full_name Customer full name.
 * @param string $email     Customer email address.
 * @param string $contact   Customer contact number.
 * @param string $address   Customer address.
 * @param string $password  Customer password (plain text in this project).
 *
 * @return string Empty string on success, or an error message on failure.
 */
function registerCustomer(mysqli $conn, string $full_name, string $email, string $contact, string $address, string $password): string
{
    // Check if email already exists
    $check = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    if (!$check) {
        return "Query failed: " . $conn->error;
    }

    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        return "Email already registered. Please login.";
    }

    // Insert customer (plain password)
    $stmt = $conn->prepare(
        "INSERT INTO customers (full_name, email, contact, address, password)
         VALUES (?, ?, ?, ?, ?)"
    );
    if (!$stmt) {
        $check->close();
        return "Query failed: " . $conn->error;
    }

    $stmt->bind_param("sssss", $full_name, $email, $contact, $address, $password);

    if ($stmt->execute()) {
        // On success, set session and redirect
        $_SESSION['user_id']   = $stmt->insert_id;
        $_SESSION['full_name'] = $full_name;

        $stmt->close();
        $check->close();

        header("Location: select_game.php");
        exit;
    }

    $errorMessage = "Registration failed: " . $conn->error;

    $stmt->close();
    $check->close();

    return $errorMessage;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** @var string $full_name Customer full name from form input */
    $full_name = trim($_POST['full_name']);

    /** @var string $email Customer email from form input */
    $email     = trim($_POST['email']);

    /** @var string $contact Customer contact number from form input */
    $contact   = trim($_POST['contact']);

    /** @var string $address Customer address from form input */
    $address   = trim($_POST['address']);

    /** @var string $password Customer password from form input (plain text) */
    $password  = trim($_POST['password']);

    // Attempt to register the customer and capture any error message
    $error = registerCustomer($conn, $full_name, $email, $contact, $address, $password);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration - Turfmate</title>
</head>
<body>

<h2>Customer Registration</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post">

    <label>Full Name:</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contact Number:</label><br>
    <input type="text" name="contact" required><br><br>

    <label>Address:</label><br>
    <input type="text" name="address" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>

</form>

<p>Already registered? <a href="login.php">Login here</a></p>

</body>
</html>
