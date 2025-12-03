<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$all = $conn->query("
    SELECT b.booking_id, b.booking_date, b.slot_time, b.status,
           c.full_name, gh.game_name
    FROM bookings b
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN game_halls gh ON b.hall_id = gh.hall_id
    ORDER BY b.booking_id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>All Bookings - Read Only</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">
<h2 class="mb-3">All Bookings (Read-Only)</h2>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Turf</th>
    <th>Slot Time</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
<?php while($b = $all->fetch_assoc()): ?>
<tr>
    <td><?= $b['booking_id'] ?></td>
    <td><?= $b['full_name'] ?></td>
    <td><?= $b['game_name'] ?></td>
    <td><?= $b['slot_time'] ?></td>
    <td><?= $b['booking_date'] ?></td>
    <td>
        <span class="badge bg-<?= $b['status']=='confirmed'?'success':'danger' ?>">
            <?= ucfirst($b['status']) ?>
        </span>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
