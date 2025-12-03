<?php
session_start(); // Start the session to manage admin login status

include "db.php"; // Include the database connection file

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to the admin login page
    exit(); // Stop further script execution
}

// SQL query to fetch all bookings along with customer and game hall information
$all = $conn->query("
    SELECT b.booking_id, b.booking_date, b.slot_time, b.status,
           c.full_name, gh.game_name
    FROM bookings b
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN game_halls gh ON b.hall_id = gh.hall_id
    ORDER BY b.booking_id DESC
"); // Execute the query to fetch all booking records, including related customer and game hall details
?>
<!DOCTYPE html>
<html>
<head>
<title>All Bookings - Read Only</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
</head>

<body class="container py-4">

<!-- Page Title -->
<h2 class="mb-3">All Bookings (Read-Only)</h2>

<!-- Bookings Table -->
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th> <!-- Column for Booking ID -->
    <th>Customer</th> <!-- Column for Customer's Full Name -->
    <th>Turf</th> <!-- Column for Game Hall Name -->
    <th>Slot Time</th> <!-- Column for Slot Time -->
    <th>Date</th> <!-- Column for Booking Date -->
    <th>Status</th> <!-- Column for Booking Status -->
</tr>
</thead>
<tbody>
<?php 
// Loop through the query result and display each booking in a table row
while($b = $all->fetch_assoc()): ?>
<tr>
    <td><?= $b['booking_id'] ?></td> <!-- Display Booking ID -->
    <td><?= $b['full_name'] ?></td> <!-- Display Customer's Full Name -->
    <td><?= $b['game_name'] ?></td> <!-- Display Game Hall Name -->
    <td><?= $b['slot_time'] ?></td> <!-- Display Slot Time -->
    <td><?= $b['booking_date'] ?></td> <!-- Display Booking Date -->
    <td>
        <!-- Display the status of the booking as a badge, with different colors based on the status -->
        <span class="badge bg-<?= $b['status']=='confirmed'?'success':'danger' ?>">
            <?= ucfirst($b['status']) ?> <!-- Capitalize and display the status (confirmed or canceled) -->
        </span>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
