<?php
session_start(); // Start the session to manage admin login status

include "db.php"; // Include the database connection file

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to the admin login page
    exit(); // Stop further script execution
}

// Fetch all payment records from the 'payments' table, ordered by payment_id in descending order
$pay = $conn->query("
    SELECT payment_id, booking_id, amount, payment_date, status
    FROM payments
    ORDER BY payment_id DESC
"); // Execute the query to fetch all payments details
?>
<!DOCTYPE html>
<html>
<head>
<title>Payments - Read Only</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
</head>

<body class="container py-4">

<!-- Page Title -->
<h2 class="mb-3">Payment History (Read-Only)</h2>

<!-- Payments Table -->
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th> <!-- Column for Payment ID -->
    <th>Booking</th> <!-- Column for Booking ID -->
    <th>Amount</th> <!-- Column for Payment Amount -->
    <th>Date</th> <!-- Column for Payment Date -->
    <th>Status</th> <!-- Column for Payment Status -->
</tr>
</thead>
<tbody>
<?php 
// Loop through the query result and display each payment in a table row
while($p = $pay->fetch_assoc()): ?>
<tr>
    <td><?= $p['payment_id'] ?></td> <!-- Display Payment ID -->
    <td><?= $p['booking_id'] ?></td> <!-- Display Booking ID -->
    <td><?= $p['amount'] ?> Tk</td> <!-- Display Payment Amount -->
    <td><?= $p['payment_date'] ?></td> <!-- Display Payment Date -->
    <td>
        <!-- Display the payment status with different colors based on the status -->
        <span class="badge bg-<?= $p['status']=='successful'?'success':'danger' ?>">
            <?= ucfirst($p['status']) ?> <!-- Capitalize and display the status (successful or failed) -->
        </span>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
