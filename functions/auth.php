<?php
/**
 * Authentication Functions
 *
 * Handles customer registration and login.
 * 
 * @author 
 * @created 2025-11-29
 */

require_once __DIR__ . '/../config/db.php';

/**
 * Registers a new customer.
 *
 * @param string $fullName  Customer full name.
 * @param string $email     Customer email.
 * @param string $password  Customer password.
 *
 * @return array Returns status and message.
 */
function registerCustomer(string $fullName, string $email, string $password): array
{
    global $conn;

    // Input validation
    if (empty($fullName) || empty($email) || empty($password)) {
        return ['status' => false, 'message' => 'All fields are required.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['status' => false, 'message' => 'Invalid email format.'];
    }

    // Check existing email
    $checkStmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        return ['status' => false, 'message' => 'Email already registered.'];
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new customer
    $stmt = $conn->prepare("INSERT INTO customers (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        return ['status' => true, 'message' => 'Registration successful.'];
    }

    return ['status' => false, 'message' => 'Registration failed. Try again.'];
}

/**
 * Logs in a customer.
 *
 * @param string $email    Customer email.
 * @param string $password Customer password.
 *
 * @return array Returns status, message, and customer ID.
 */
function loginCustomer(string $email, string $password): array
{
    global $conn;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['status' => false, 'message' => 'Invalid email format.'];
    }

    $stmt = $conn->prepare("SELECT customer_id, password FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ['status' => false, 'message' => 'Invalid email or password.'];
    }

    $user = $result->fetch_assoc();
}