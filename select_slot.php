<?php
/**
 * Slot Selection Page
 *
 * Displays available booking slots for a selected game hall. Users choose a
 * date, view available 2-hour time slots (8AM–10PM), and proceed to checkout
 * if the slot is not already booked. This page requires an authenticated user.
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

if (!isset($_GET['game'])) {
    header("Location: dashboard.php");
    exit;
}

$game = strtolower(trim($_GET['game']));

// get hall from game_halls using game_name
$stmt = $conn->prepare("SELECT hall_id, game_name, price FROM game_halls WHERE LOWER(game_name) = ?");
$stmt->bind_param("s", $game);
$stmt->execute();
$stmt->bind_result($hall_id, $game_name, $price);
if (!$stmt->fetch()) {
    $stmt->close();
    die("Invalid game selected.");
}
$stmt->close();

// define slots 8am - 10pm, 2h each
$slots = [
    "08:00 AM - 10:00 AM",
    "10:00 AM - 12:00 PM",
    "12:00 PM - 02:00 PM",
    "02:00 PM - 04:00 PM",
    "04:00 PM - 06:00 PM",
    "06:00 PM - 08:00 PM",
    "08:00 PM - 10:00 PM"
];

// selected date (default: today)
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Slot - <?php echo htmlspecialchars($game_name); ?></title>
</head>
<body>
<h2>Select Slot for: <?php echo htmlspecialchars($game_name); ?></h2>
<p><a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a></p>

<form method="get" action="select_slot.php">
    <input type="hidden" name="game" value="<?php echo htmlspecialchars($game); ?>">
    <label>Select Date:</label>
    <input type="date" name="date" value="<?php echo htmlspecialchars($selected_date); ?>" required>
    <button type="submit">Show Slots</button>
</form>

<h3>Slots for <?php echo htmlspecialchars($selected_date); ?></h3>

<table border="1" cellpadding="8">
    <tr>
        <th>Slot</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
<?php
foreach ($slots as $slot) {
    // check if slot already booked for this hall & date
    $q = $conn->prepare("SELECT COUNT(*) 
                         FROM bookings 
                         WHERE hall_id = ? 
                           AND booking_date = ? 
                           AND slot_time = ?
                           AND status IN ('pending', 'confirmed')");
    $q->bind_param("iss", $hall_id, $selected_date, $slot);
    $q->execute();
    $q->bind_result($cnt);
    $q->fetch();
    $q->close();

    $isBooked = ($cnt > 0);

    echo "<tr>";
    echo "<td>" . htmlspecialchars($slot) . "</td>";

    if ($isBooked) {
        echo "<td style='color:red;'>Booked</td><td>-</td>";
    } else {
        echo "<td style='color:green;'>Available</td>";
        echo "<td>
                <form method=\"post\" action=\"checkout.php\">
                    <input type=\"hidden\" name=\"hall_id\" value=\"$hall_id\">
                    <input type=\"hidden\" name=\"booking_date\" value=\"" . htmlspecialchars($selected_date) . "\">
                    <input type=\"hidden\" name=\"slot_time\" value=\"" . htmlspecialchars($slot) . "\">
                    <button type=\"submit\">Proceed to Checkout</button>
                </form>
              </td>";
    }

    echo "</tr>";
}
?>
</table>

</body>
</html>
