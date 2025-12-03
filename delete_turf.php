<?php
include "db.php"; // Include the database connection file

// Get the 'id' parameter from the URL
$id = $_GET['id'];

/**
 * Perform a DELETE query to remove the turf from the 'game_halls' table
 * based on the provided 'hall_id' (ID of the turf to be deleted)
 */
$conn->query("DELETE FROM game_halls WHERE hall_id=$id");

// After the deletion, redirect to the 'manage_turfs.php' page to refresh the list of turfs
header("Location: manage_turfs.php");
exit(); // Ensure no further code is executed after the redirect
?>
