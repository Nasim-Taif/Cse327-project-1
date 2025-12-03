<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$halls = $conn->query("SELECT * FROM game_halls ORDER BY hall_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Turfs</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-3">Manage Game Halls</h2>

    <a href="add_turf.php" class="btn btn-success mb-3">Add New Hall</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                
                <th>Game Name</th>
                <th>Price</th>
                <th>Description</th>
                <th width="20%">Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = $halls->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['hall_id'] ?></td>
               
                <td><?= htmlspecialchars($row['game_name']) ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>

                <td>
                    <a href="edit_turf.php?id=<?= $row['hall_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete_turf.php?id=<?= $row['hall_id'] ?>" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Delete this hall?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
