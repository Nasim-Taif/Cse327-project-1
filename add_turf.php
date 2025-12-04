<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $game_name = $_POST['game_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    $stmt = $conn->prepare("INSERT INTO game_halls (game_name, price, description, image) 
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $game_name, $price, $description, $image);
    $stmt->execute();

    header("Location: manage_turfs.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Hall</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-5">
    <h2>Add New Game Hall</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Game Name</label>
        <input type="text" name="game_name" class="form-control" required>

        <label class="mt-2">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>

        <label class="mt-2">Description</label>
        <textarea name="description" class="form-control"></textarea>

        <label class="mt-2">Image</label>
        <input type="file" name="image" class="form-control">

        <button class="btn btn-success mt-3">Add Hall</button>
    </form>
</div>
</body>
</html>
