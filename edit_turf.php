<?php
session_start();
include "db.php";

$id = $_GET['id'];

$hall = $conn->query("SELECT * FROM game_halls WHERE hall_id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['game_name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    $image = $hall['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    $stmt = $conn->prepare("UPDATE game_halls SET game_name=?, price=?, description=?, image=? WHERE hall_id=?");
    $stmt->bind_param("sdssi", $name, $price, $desc, $image, $id);
    $stmt->execute();

    header("Location: manage_turfs.php");
    exit();
}
?>
