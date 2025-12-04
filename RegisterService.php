<?php

class RegisterService
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Extracts the errno from mysqli_stmt.
     * This wrapper is needed because mysqli_stmt::$errno is read-only.
     */
    protected function getStmtErrno(mysqli_stmt $stmt): int
    {
        return $stmt->errno;  
    }

    public function registerCustomer(string $name, string $email, string $password)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO customers (name, email, password) VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            return "Registration failed";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if (!$stmt->execute()) {

            $errno = $this->getStmtErrno($stmt);

            if ($errno === 1062) {
                return "Email already exists";
            }

            return "Registration failed";
        }

        return true;
    }
}
