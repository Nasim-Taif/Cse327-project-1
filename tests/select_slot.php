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

// Restrict access to logged-in customers
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';
require 'slot_helper.php';


/**
 * Ensure the 'game' parameter is provided.
 * Redirects to dashboard if accessed incorrectly.
 */
if (!isset($_GET['game'])) {
    header("Location: dashboard.php");
    exit;
}

/** @var string $game Game identifier from URL (e.g., 'badminton') */
$game = strtolower(trim($_GET['game']));

/**
 * Fetch hall info (hall_id, game_name, price)
 * based on game name selected by the user.
 */
$stmt = $conn->prepare(
    "SELECT hall_id, game_name, price 
     FROM game_halls 
     WHERE LOWER(game_name) = ?"
);
$stmt->bind_param("s", $game);
$stmt->execute();
$stmt->bind_result($hall_id, $game_name, $price);

if (!$stmt->fetch()) {
    $stmt->close();
    die("Invalid game selected.");
}

$stmt->close();

/**
 * Predefined game slots (2-hour each from 8AM to 10PM)
 *
 * @var array<string>
 */
$slots = [
    "08:00 AM - 10:00 AM",
    "10:00 AM - 12:00 PM",
    "12:00 PM - 02:00 PM",
    "02:00 PM - 04:00 PM",
    "04:00 PM - 06:00 PM",
    "06:00 PM - 08:00 PM",
    "08:00 PM - 10:00 PM"
];

/**
 * Determine the date to display slots for.
 * Default = today's date.
 *
 * @var string $selected_date Date in Y-m-d format
 */
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Slot - <?php echo htmlspecialchars($game_name); ?></title>
</head>
<body>

<h2>Select Slot for: <?php echo htmlspecialchars($game_name); ?></h2>

<p>
    <a href="dashboard.php">Back to Dashboard</a> | 
    <a href="logout.php">Logout</a>
</p>

<!-- Date selection form -->
<form method="get" action="select_slot.php">
    <input type="hidden" name="game" value="<?php echo htmlspecialchars($game); ?>">
    
    <label>Select Date:</label>
    <input type="date" name="date" 
           value="<?php echo htmlspecialchars($selected_date); ?>" required>
    
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
/**
 * Loop through slot list and check availability.
 */

    // Check if slot is already booked
  foreach ($slots as $slot) {

    /** @var bool $isBooked Indicates if slot is taken */
    $isBooked = isSlotBooked($conn, $hall_id, $selected_date, $slot);

    echo "<tr>";
    echo "<td>" . htmlspecialchars($slot) . "</td>";

    if ($isBooked) {
        echo "<td style='color:red;'>Booked</td><td>-</td>";
    } else {
        // ... keep your existing available HTML here ...
    }

    echo "</tr>";
}

?>
</table>

</body>
</html>
