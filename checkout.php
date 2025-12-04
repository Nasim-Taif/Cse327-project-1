<?php
/**
 * Checkout Page
 *
 * Handles slot re-validation, booking confirmation, and payment processing
 * for the Turfmate booking system. If accessed without POST data, the
 * user is redirected to the dashboard. Only authenticated users can
 * finalize bookings.
 *
 * PHP version 8+
 *
 * @category  Turfmate
 * @package   TurfmateBookingSystem
 * @author    
 * @created   2025-11-29
 */
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

$customer_id  = $_SESSION['user_id'];
$hall_id      = (int)$_POST['hall_id'];
$booking_date = $_POST['booking_date'];
$slot_time    = $_POST['slot_time'];

// get hall info
$stmt = $conn->prepare("SELECT game_name, price FROM game_halls WHERE hall_id = ?");
$stmt->bind_param("i", $hall_id);
$stmt->execute();
$stmt->bind_result($game_name, $price);
if (!$stmt->fetch()) {
    $stmt->close();
    die("Invalid hall.");
}
$stmt->close();

$message = "";

if (isset($_POST['pay_now'])) {
    // check again if slot is still free
    $q = $conn->prepare("SELECT COUNT(*) 
                         FROM bookings 
                         WHERE hall_id = ? 
                           AND booking_date = ? 
                           AND slot_time = ?
                           AND status IN ('pending', 'confirmed')");
    $q->bind_param("iss", $hall_id, $booking_date, $slot_time);
    $q->execute();
    $q->bind_result($cnt);
    $q->fetch();
    $q->close();

    if ($cnt > 0) {
        $message = "Sorry, this slot has just been booked by someone else.";
    } else {
        // create booking
        $status = 'confirmed';
        $b = $conn->prepare("INSERT INTO bookings (customer_id, hall_id, booking_date, slot_time, status) 
                             VALUES (?, ?, ?, ?, ?)");
        $b->bind_param("iisss", $customer_id, $hall_id, $booking_date, $slot_time, $status);
        if ($b->execute()) {
            $booking_id = $b->insert_id;
            $b->close();

            // record payment (demo: always successful)
            $pstatus = 'successful';
            $p = $conn->prepare("INSERT INTO payments (booking_id, amount, status) 
                                 VALUES (?, ?, ?)");
            $p->bind_param("ids", $booking_id, $price, $pstatus);
            $p->execute();
            $p->close();

            $message = "Booking and payment successful! Your booking ID is #" . $booking_id;
        } else {
            $message = "Booking failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Turfmate</title>
</head>
<body>
<h2>Checkout</h2>
<p><a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a></p>

<h3>Booking Details</h3>
<ul>
    <li>Game: <?php echo htmlspecialchars($game_name); ?></li>
    <li>Date: <?php echo htmlspecialchars($booking_date); ?></li>
    <li>Slot: <?php echo htmlspecialchars($slot_time); ?></li>
    <li>Price: <?php echo number_format($price, 2); ?></li>
</ul>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php else: ?>
<h3>Payment (Demo)</h3>
<form method="post">
    <input type="hidden" name="hall_id" value="<?php echo $hall_id; ?>">
    <input type="hidden" name="booking_date" value="<?php echo htmlspecialchars($booking_date); ?>">
    <input type="hidden" name="slot_time" value="<?php echo htmlspecialchars($slot_time); ?>">

    <label>Card Holder Name:</label><br>
    <input type="text" name="card_name" required><br><br>

    <label>Card Number:</label><br>
    <input type="text" name="card_number" required><br><br>

    <label>Expiry (MM/YY):</label><br>
    <input type="text" name="expiry" required><br><br>

    <label>CVV:</label><br>
    <input type="password" name="cvv" required><br><br>

    <button type="submit" name="pay_now">
        Pay <?php echo number_format($price, 2); ?>
    </button>
</form>
<?php endif; ?>

</body>
</html>
