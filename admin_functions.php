<?php
// Prevent multiple session_start calls
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection only once
include_once "db.php";

/**
 * Attempt admin login
 *
 * @param mysqli $conn
 * @param string $email
 * @param string $password
 * @return array|null Returns admin array if success, null if fail
 */
function adminLogin($conn, string $email, string $password): ?array {
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Ideally use password_hash() & password_verify()
        if ($password === $admin['password']) {
            return $admin;
        }
    }

    return null;
}
