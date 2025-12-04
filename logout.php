<?php
/**
 * Logout Script
 *
 * This script logs out the currently authenticated customer by clearing
 * all session data and destroying the active session. After logout, the
 * user is redirected to the login page.
 *
 * PHP version 8+
 *
 * @category  Turfmate
 * @package   TurfmateBookingSystem
 * @author    
 * @created   2025-11-29
 */
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
