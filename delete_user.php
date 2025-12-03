<?php
include "db.php"; // Include the database connection file

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the 'id' from the URL (customer_id)
    $id = $_GET['id'];

    /**
     * Perform a DELETE query to remove the user from the 'customers' table
     * based on the provided 'customer_id' (ID of the user to be deleted)
     */
    $conn->query("DELETE FROM customers WHERE customer_id = $id");
}

// After the deletion, redirect to the 'manage_users.php' page to refresh the list of users
header("Location: manage_users.php");
exit(); // Ensure no further code is executed after the redirect
?>
