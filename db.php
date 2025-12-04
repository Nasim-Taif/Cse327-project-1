<?php
/**
 * Homepage
 * Displays main introduction, gallery, and Book Now button.
 *
 * @package Turfmate
 */
// db.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "turfmate";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
