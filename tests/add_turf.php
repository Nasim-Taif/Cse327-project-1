<?php
/**
 * Adds a new game hall to the system.
 * 
 * This script processes the form submission to add a new game hall, including handling the game name, 
 * price, description, and image upload. The data is inserted into the 'game_halls' table in the database.
 * If an image is uploaded, it is moved to the "uploads" directory.
 *
 * @package TurfMate
 * @subpackage Admin
 * @version 1.0
 */

session_start();
include "db.php"; // Include the database connection file

/**
 * Handles the POST request to add a new game hall.
 * 
 * It processes the form data, uploads the image (if any), and inserts the new hall details into the database.
 *
 * @return void
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect form data
    /**
     * @var string $game_name The name of the game being added.
     */
    $game_name = $_POST['game_name'];

    /**
     * @var float $price The price for booking the game hall.
     */
    $price = $_POST['price'];

    /**
     * @var string $description The description of the game hall.
     */
    $description = $_POST['description'];

    /**
     * @var string|null $image The filename of the uploaded image.
     * If no image is uploaded, it will be null.
     */
    $image = null;

    // Check if an image has been uploaded
    if (!empty($_FILES['image']['name'])) {
        /**
         * @var string $image The unique filename generated for the uploaded image.
         */
        $image = time() . "_" . $_FILES['image']['name'];

        // Move the uploaded file to the 'uploads' directory
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    // Prepare the SQL query to insert game hall details
    /**
     * @var mysqli_stmt $stmt The prepared statement for executing the SQL query.
     */
    $stmt = $conn->prepare("INSERT INTO game_halls (game_name, price, description, image) 
                            VALUES (?, ?, ?, ?)");
    // Bind the parameters to the prepared statement
    $stmt->bind_param("sdss", $game_name, $price, $description, $image);
    // Execute the statement
    $stmt->execute();

    // Redirect to the manage turfs page after the insertion
    header("Location: manage_turfs.php");
    exit(); // Ensure no further code is executed
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

    <!-- Form to add a new game hall -->
    <form method="POST" enctype="multipart/form-data">

        <!-- Game Name Input -->
        <label>Game Name</label>
        <input type="text" name="game_name" class="form-control" required>

        <!-- Price Input -->
        <label class="mt-2">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>

        <!-- Description Textarea -->
        <label class="mt-2">Description</label>
        <textarea name="description" class="form-control"></textarea>

        <!-- Image Upload Input -->
        <label class="mt-2">Image</label>
        <input type="file" name="image" class="form-control">

        <!-- Submit Button -->
        <button class="btn btn-success mt-3">Add Hall</button>
    </form>
</div>
</body>
</html>
<?php
use PHPUnit\Framework\TestCase;

class AddTurfTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Mock or create an actual database connection
        $this->conn = new mysqli("localhost", "root", "", "turfmate");
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        $this->conn->close();
    }

    public function testAddNewTurf()
    {
        // Simulate form data for a new turf
        $_POST['game_name'] = "Test Turf";
        $_POST['price'] = 1000;
        $_POST['description'] = "A test turf for unit testing.";
        $_FILES['image'] = [
            'name' => 'test_turf.jpg',
            'tmp_name' => '/path/to/tmp/file',
            'error' => 0,
            'size' => 1000
        ];

        // Include the logic from add_turf.php that processes the form data
        include 'add_turf.php';

        // Check if the new turf has been added to the database
        $result = $this->conn->query("SELECT * FROM game_halls WHERE game_name='Test Turf'");
        $this->assertEquals(1, $result->num_rows); // Assert only one row should exist
    }

    public function testMissingGameName()
    {
        // Simulate missing game name
        $_POST['price'] = 1000;
        $_POST['description'] = "A test turf without a game name.";
        $_FILES['image'] = [
            'name' => 'test_turf.jpg',
            'tmp_name' => '/path/to/tmp/file',
            'error' => 0,
            'size' => 1000
        ];

        include 'add_turf.php';

        // Check no row is inserted due to missing game name
        $result = $this->conn->query("SELECT * FROM game_halls WHERE description='A test turf without a game name.'");
        $this->assertEquals(0, $result->num_rows); // No row should be returned
    }

    // Additional tests for invalid inputs can go here
}
