<?php
/**
 * Dashboard Page
 *
 * This page displays the available games to the logged-in customer.
 * Users must be authenticated to view the dashboard. If not logged in,
 * they are redirected to the login page.
 *
 * PHP version 8+
 *
 * @category  Turfmate
 * @package   TurfmateBookingSystem
 * @author    Your Name
 * @license   Turfmate Private
 * @link      http://localhost/turfmate/select_game.php
 */

session_start();

/**
 * Check if the user is authenticated.
 * 
 * If `user_id` does not exist in the session,
 * redirect the user to the login page and stop execution.
 *
 * @return void
 */
function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

requireLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Choose Your Game</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>

<h1>Choose Your Game</h1>

<!-- 
    Game Selection Grid
    Each game links to select_slot.php with a GET parameter.
-->

<div class="game-container">

    <a href="select_slot.php?game=badminton" class="game-card">
        <img src="images/game1.jpg" alt="Badminton">
        <div class="title">Badminton</div>
    </a>

    <a href="select_slot.php?game=football" class="game-card">
        <img src="images/game2.jpg" alt="Football">
        <div class="title">Football</div>
    </a>

    <a href="select_slot.php?game=cricket" class="game-card">
        <img src="images/game3.jpg" alt="Cricket">
        <div class="title">Cricket</div>
    </a>

    <a href="select_slot.php?game=swimming" class="game-card">
        <img src="images/game4.jpg" alt="Swimming">
        <div class="title">Swimming</div>
    </a>

    <a href="select_slot.php?game=volleyball" class="game-card">
        <img src="images/game5.jpg" alt="Volleyball">
        <div class="title">Volleyball</div>
    </a>

</div>

<!-- Logout section -->
<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
