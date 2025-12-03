<?php
session_start(); // Start a session to track user data

include "db.php"; // Include the database connection file

// Get the 'id' parameter from the URL to fetch the specific game hall's data
$id = $_GET['id'];

// Fetch the current details of the game hall from the database using the provided 'hall_id'
$hall = $conn->query("SELECT * FROM game_halls WHERE hall_id=$id")->fetch_assoc();

/**
 * Check if the form has been submitted (POST request)
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the updated data from the form fields
    $name = $_POST['game_name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    // Set the default image as the current image of the hall
    $image = $hall['image'];

    /**
     * Check if a new image has been uploaded
     * If a new image is uploaded, generate a new filename with a timestamp to avoid name conflicts
     */
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name']; // Create a unique image name
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image); // Move the uploaded image to the "uploads" directory
    }

    // Prepare an UPDATE query to update the game hall details in the database
    $stmt = $conn->prepare("UPDATE game_halls SET game_name=?, price=?, description=?, image=? WHERE hall_id=?");
    $stmt->bind_param("sdssi", $name, $price, $desc, $image, $id); // Bind parameters for the prepared statement
    $stmt->execute(); // Execute the update query

    // Redirect to the 'manage_turfs.php' page after successful update
    header("Location: manage_turfs.php");
    exit(); // Stop further execution of the script after the redirect
}
?>
