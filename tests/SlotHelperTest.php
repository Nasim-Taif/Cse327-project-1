<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../slot_helper.php';

final class SlotHelperTest extends TestCase
{
    private mysqli $conn;

    // Use real IDs from your DB:
    private int $hallId     = 1; // existing hall_id in game_halls
    private int $customerId = 1; // existing customer_id in customers

    private string $date = '2025-12-31';
    private string $slot = '08:00 AM - 10:00 AM';

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', '', 'turfmate');

        if ($this->conn->connect_error) {
            $this->fail('DB connection failed: ' . $this->conn->connect_error);
        }

        // Clean previous test data
        $stmt = $this->conn->prepare(
            "DELETE FROM bookings 
             WHERE hall_id = ? AND booking_date = ? AND slot_time = ?"
        );
        $stmt->bind_param('iss', $this->hallId, $this->date, $this->slot);
        $stmt->execute();
        $stmt->close();
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testIsSlotBookedReturnsFalseWhenNoBooking(): void
    {
        $this->assertFalse(
            isSlotBooked($this->conn, $this->hallId, $this->date, $this->slot)
        );
    }

    public function testIsSlotBookedReturnsTrueWhenBookingExists(): void
    {
        // ✅ Insert a booking that satisfies the FK on customer_id
        $stmt = $this->conn->prepare(
            "INSERT INTO bookings (hall_id, customer_id, booking_date, slot_time, status)
             VALUES (?, ?, ?, ?, 'confirmed')"
        );
        $stmt->bind_param(
            'iiss',
            $this->hallId,
            $this->customerId,
            $this->date,
            $this->slot
        );
        $stmt->execute();
        $stmt->close();

        // Now helper should say it's booked
        $this->assertTrue(
            isSlotBooked($this->conn, $this->hallId, $this->date, $this->slot)
        );

        // Clean up
        $stmt = $this->conn->prepare(
            "DELETE FROM bookings 
             WHERE hall_id = ? AND booking_date = ? AND slot_time = ?"
        );
        $stmt->bind_param('iss', $this->hallId, $this->date, $this->slot);
        $stmt->execute();
        $stmt->close();
    }
}
