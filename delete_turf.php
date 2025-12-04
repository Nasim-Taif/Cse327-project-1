<?php
include "db.php";

$id = $_GET['id'];

$conn->query("DELETE FROM game_halls WHERE hall_id=$id");

header("Location: manage_turfs.php");
exit();
?>
