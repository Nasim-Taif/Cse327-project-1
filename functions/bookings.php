<?php
/**
 * Booking Functions
 *
 * Handles slot availability, booking and payment processing.
 * 
 * @author 
 * @created 2025-11-29
 */

require_once __DIR__ . '/../config/db.php';

/**
 * Returns available game halls.
 *
 * @return array List of game halls.
 */
function getGameHalls(): array
{
    global $conn;

    $query = "SELECT hall_id, game_name, price, description, image FROM game_halls";
    $result = $conn->query($query);

    $halls = [];

    while ($row = $result->fetch_assoc()) {
        $halls[] = $row;
    }

    return $halls;
}

/**
 * Checks if a given slot on a given date is available for booking.
 *
 * @param int    $hallId       Game hall ID.
 * @param string $bookingDate  Date (YYYY-MM-DD).
 * @param string $slotTime     Slot time (e.g., "8-10AM").
 *
 * @return bool True if available, false if booked.
 */
function isSlotAvailable(int $hallId, string $bookingDate, string $slotTime): bool
{
    global $conn;

    $stmt = $conn->prepare("SELECT booking_id FROM bookings 
        WHERE hall_id = ? AND booking_date = ? AND slot_time = ? AND status = 'confirmed'");
    $stmt->bind_param("iss", $hallId, $bookingDate, $slotTime);
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result->num_rows === 0);
}

/**
 * Creates a new booking.
 *
 * @param int    $customerId  Customer ID.
 * @param int    $hallId      Game hall ID.
 * @param string $bookingDate Date selected.
 * @param string $slotTime    Slot time.
 *
 * @return array Status and message.
 */
function createBooking(int $customerId, int $hallId, string $bookingDate, string $slotTime): array
{
    global $conn;

    if (!isSlotAvailable($hallId, $bookingDate, $slotTime)) {
        return ['status' => false, 'message' => 'Selected slot is already booked.'];
    }

    $stmt = $conn->prepare("INSERT INTO bookings (customer_id, hall_id, booking_date, slot_time, status) 
                            VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iiss", $customerId, $hallId, $bookingDate, $slotTime);

    if ($stmt->execute()) {
        return ['status' => true, 'message' => 'Slot reserved. Proceed to payment.', 'booking_id' => $stmt->insert_id];
    }

    return ['status' => false, 'message' => 'Booking failed. Try again.'];
}

/**
 * Processes payment for a booking.
 *
 * @param int   $bookingId Booking ID.
 * @param float $amount    Payment amount.
 *
 * @return array Status and message.
 */
function processPayment(int $bookingId, float $amount): array
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO payments (booking_id, amount, status) 
                            VALUES (?, ?, 'successful')");
    $stmt->bind_param("id", $bookingId, $amount);

    if ($stmt->execute()) {
        // mark booking as confirmed
        $updateStmt = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?");
        $updateStmt->bind_param("i", $bookingId);
        $updateStmt->execute();

        return ['status' => true, 'message' => 'Payment successful. Booking confirmed!'];
    }

    return ['status' => false, 'message' => 'Payment failed.'];
}
