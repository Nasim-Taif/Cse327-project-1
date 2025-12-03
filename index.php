<?php 
/**
 * Homepage
 * Displays main introduction, gallery, and Book Now button.
 *
 * @package Turfmate
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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turfmate</title>
    <style>
        /* General Styles for the About Section */
        .about-section {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            padding: 60px 0;
            text-align: center;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        /* Container for centering content */
        .about-section .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Heading for section */
        .about-section h2 {
            font-size: 42px;
            font-weight: 700;
            color: #0056b3;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Paragraph Styling */
        .about-section p {
            font-size: 18px;
            line-height: 1.7;
            color: #555;
            margin-bottom: 30px;
        }

        /* Styling for Features List */
        .features {
            text-align: left;
            margin-bottom: 40px;
        }

        .features h3 {
            font-size: 28px;
            font-weight: 600;
            color: #444;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* List Items for Features */
        .features ul {
            list-style-type: none;
            padding: 0;
        }

        .features li {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
            position: relative;
            padding-left: 30px;
        }

        .features li strong {
            color: #0056b3;  /* Highlighted feature names */
        }

        /* Bullet Points for List */
        .features li:before {
            content: '✔';
            position: absolute;
            left: 0;
            top: 0;
            font-size: 20px;
            color: #28a745;
        }

        /* Button Styling */
        .btn-book-now {
            display: inline-block;
            padding: 15px 35px;
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-book-now:hover {
            background-color: #003c8f;
            transform: translateY(-3px);
        }

       /* Footer Section Styling */
.footer-section {
    background-color: #2c3e50;
    color: #fff;
    padding: 40px 0;
    font-family: 'Roboto', sans-serif;
}

/* Footer Content: Adjust padding */
.footer-content {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 30px;
    padding: 0 20px; /* Added padding for better alignment */
}

/* Footer Logo */
.footer-logo h3 {
    font-size: 28px;
    color: #fff;
    margin-bottom: 10px;
}

.footer-logo p {
    font-size: 16px;
    color: #ccc;
    margin-top: 0;
}

/* Footer Links */
.footer-links h4,
.footer-contact h4 {
    font-size: 20px;
    color: #fff;
    margin-bottom: 15px;
}

.footer-links ul {
    list-style-type: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    text-decoration: none;
    color: #ccc;
    font-size: 16px;
}

.footer-links a:hover {
    color: #f39c12;
}

/* Footer Contact Info */
.footer-contact p {
    font-size: 16px;
    color: #ccc;
}

/* Footer Bottom Section */
.footer-bottom {
    text-align: center;
    font-size: 16px;
    color: #ccc;
    border-top: 1px solid #444;
    padding-top: 20px;
    padding-bottom: 20px; /* Adjusted padding */
}

.footer-bottom p {
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        align-items: center;
        padding: 0 15px; /* Added padding for mobile */
    }

    .footer-logo,
    .footer-links,
    .footer-contact {
        margin-bottom: 30px;
        text-align: center;
    }

    .footer-logo h3 {
        font-size: 24px;
    }

    .footer-links ul {
        padding-left: 0;
    }

    .footer-contact p {
        font-size: 14px;
    }

    .footer-bottom {
        padding-top: 10px;
        padding-bottom: 15px;
    }
}

    </style>
</head>
<body>
    <section id="about" class="about-section">
        <div class="container">
            <h2>About Turfmate</h2>
            <p>Welcome to <strong>Turfmate</strong> — your one-stop platform for booking sports facilities with ease and security. Whether you're a casual player or a professional athlete, Turfmate offers a variety of game halls for you to choose from. We bring the convenience of online booking to your fingertips, so you can focus on what you do best — playing the game you love!</p>

            <div class="features">
                <h3>Our Features:</h3>
                <ul>
                    <li><strong>Wide Range of Sports:</strong> From football and cricket to badminton and swimming, we offer facilities for all your favorite sports.</li>
                    <li><strong>Seamless Booking:</strong> Book your desired game hall in just a few clicks with our user-friendly interface.</li>
                    <li><strong>Real-Time Availability:</strong> Check and select available slots in real-time, ensuring your time is well spent.</li>
                    <li><strong>Secure Payment:</strong> Our platform offers safe and secure online payment options for hassle-free transactions.</li>
                   
            </div>

            <p>At Turfmate, we strive to make sports booking as easy and enjoyable as the game itself. Join us today and book your next game session with confidence!</p>

            <a href="select_game.php" class="btn-book-now">Book Your Game Now</a>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer-section">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>Turfmate</h3>
                    <p>Your one-stop platform for booking sports facilities.</p>
                </div>

                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h4>Contact Us</h4>
                    <p><strong>Email:</strong> support@turfmate.com</p>
                    <p><strong>Phone:</strong> +1 234 567 890</p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 Turfmate. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>



</body>
</html>
