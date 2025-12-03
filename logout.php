<?php
/**
 * Homepage
 * Displays main introduction, gallery, and Book Now button.
 *
 * @package Turfmate
 */
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
