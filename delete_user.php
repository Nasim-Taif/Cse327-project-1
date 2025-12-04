<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user
    $conn->query("DELETE FROM customers WHERE customer_id = $id");
}

header("Location: manage_users.php");
exit();
?>
