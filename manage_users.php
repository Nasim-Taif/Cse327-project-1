<<<<<<< HEAD
<?php
session_start();
include "db.php";

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch all customers
$users = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Users</h2>
        <a href="register.php" class="btn btn-primary">+ Add User Manually</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $users->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['customer_id'] ?></td>
                <td><?= $row['full_name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['contact'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="delete_user.php?id=<?= $row['customer_id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this user?')">
                       Delete
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
include "db.php";

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch all customers
$users = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Users</h2>
        <a href="register.php" class="btn btn-primary">+ Add User Manually</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $users->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['customer_id'] ?></td>
                <td><?= $row['full_name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['contact'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="delete_user.php?id=<?= $row['customer_id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this user?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
