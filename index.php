<?php
/**
 * Home Page (Landing Page)
 *
 * This is the main landing page of the Turfmate booking system. It displays
 * a welcome message, a link to book available game halls, a gallery preview
 * of sports images, and an informational About section.
 *
 * The header and navigation bar are included from the shared layout files.
 *
 * PHP version 8+
 *
 * @category  Turfmate
 * @package   TurfmateBookingSystem
 * @author    
 * @created   2025-11-29
 */
include "includes/header.php"; 
include "includes/navbar.php";
?>

<section class="home-container">
    <h1>Welcome to Turfmate</h1>
    <p>Your trusted game hall booking partner.</p>

    <a href="select_game.php" class="book-btn">Book Now!</a>

    <div class="game-gallery">
        <img src="images/game1.jpg" alt="Badminton">
        <img src="images/game2.jpg" alt="Football">
        <img src="images/game3.jpg" alt="Cricket">
        <img src="images/game4.jpg" alt="Swimming">
        <img src="images/game5.jpg" alt="Volleyball">
    </div>
</section>

<section id="about" class="about-section">
    <h2>About Turfmate</h2>
    <p>Turfmate is a modern platform to book sports facilities easily and securely.</p>
</section>

</body>
</html>
