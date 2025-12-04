<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$pay = $conn->query("
    SELECT payment_id, booking_id, amount, payment_date, status
    FROM payments
    ORDER BY payment_id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Payments - Read Only</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">
<h2 class="mb-3">Payment History (Read-Only)</h2>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Booking</th>
    <th>Amount</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
<?php while($p = $pay->fetch_assoc()): ?>
<tr>
    <td><?= $p['payment_id'] ?></td>
    <td><?= $p['booking_id'] ?></td>
    <td><?= $p['amount'] ?> Tk</td>
    <td><?= $p['payment_date'] ?></td>
    <td>
        <span class="badge bg-<?= $p['status']=='successful'?'success':'danger' ?>">
            <?= ucfirst($p['status']) ?>
        </span>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
