<?php
/**
 * Helper functions related to slot availability.
 *
 * PHP version 8+
 *
 * @category  Turfmate
 * @package   TurfmateBookingSystem
 */

/**
 * Check whether a slot is already booked.
 *
 * A slot is considered booked if there is at least one row in `bookings`
 * for the given hall, date and slot_time, with status 'pending' or 'confirmed'.
 *
 * @param mysqli $conn   Active MySQLi connection
 * @param int    $hallId Hall identifier
 * @param string $date   Booking date in Y-m-d format
 * @param string $slot   Slot text, e.g. "08:00 AM - 10:00 AM"
 *
 * @return bool True if the slot is booked, false otherwise
 */
function isSlotBooked(mysqli $conn, int $hallId, string $date, string $slot): bool
{
    $q = $conn->prepare(
        "SELECT COUNT(*) 
         FROM bookings 
         WHERE hall_id = ? 
           AND booking_date = ? 
           AND slot_time = ?
           AND status IN ('pending', 'confirmed')"
    );
    $q->bind_param("iss", $hallId, $date, $slot);
    $q->execute();
    $q->bind_result($cnt);
    $q->fetch();
    $q->close();

    return $cnt > 0;
}
