<<<<<<< HEAD
<?php
session_start();
include "db.php"; // database connection

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch slot bookings with customer + hall info
$sql = "
    SELECT 
        b.booking_id,
        c.full_name AS customer_name,
        c.email,
        c.contact,
        h.game_name,
        b.slot_time,
        b.booking_date,
        b.status
    FROM bookings b
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN game_halls h ON b.hall_id = h.hall_id
    ORDER BY b.booking_date DESC, b.slot_time ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Slots</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

<!-- Header with Make a Booking button -->
<h2 class="mb-4 d-flex justify-content-between align-items-center">
    Manage Slots
    <a href="select_game.php" class="btn btn-primary">
        + Make a Booking
    </a>
</h2>

<!-- Slots Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Hall</th>
            <th>Slot Time</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['booking_id']) ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['game_name']) ?></td>
            <td><?= htmlspecialchars($row['slot_time']) ?></td>
            <td><?= htmlspecialchars($row['booking_date']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <a href="cancel_slot.php?id=<?= $row['booking_id'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Cancel this booking?')">
                   Cancel
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
=======
<?php
session_start();
include "db.php"; // database connection

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch slot bookings with customer + hall info
$sql = "
    SELECT 
        b.booking_id,
        c.full_name AS customer_name,
        c.email,
        c.contact,
        h.game_name,
        b.slot_time,
        b.booking_date,
        b.status
    FROM bookings b
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN game_halls h ON b.hall_id = h.hall_id
    ORDER BY b.booking_date DESC, b.slot_time ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Slots</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

<!-- Header with Make a Booking button -->
<h2 class="mb-4 d-flex justify-content-between align-items-center">
    Manage Slots
    <a href="select_game.php" class="btn btn-primary">
        + Make a Booking
    </a>
</h2>

<!-- Slots Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Hall</th>
            <th>Slot Time</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['booking_id']) ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['game_name']) ?></td>
            <td><?= htmlspecialchars($row['slot_time']) ?></td>
            <td><?= htmlspecialchars($row['booking_date']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <a href="cancel_slot.php?id=<?= $row['booking_id'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Cancel this booking?')">
                   Cancel
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
>>>>>>> 493131e4742ebc2589ee5c365bbc0ff8dd020d14
