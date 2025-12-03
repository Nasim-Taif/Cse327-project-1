<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../admin_functions.php';

class AdminLoginTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli("localhost", "root", "", "turfmate");
        if ($this->conn->connect_error) {
            $this->fail("DB connection failed: " . $this->conn->connect_error);
        }
        // Insert test admin
        $this->conn->query("INSERT INTO admins (username, email, password) VALUES ('TestAdmin','test@example.com','12345')");
    }

    protected function tearDown(): void
    {
        // Remove test admin
        $this->conn->query("DELETE FROM admins WHERE email='test@example.com'");
        $this->conn->close();
    }

    public function testSuccessfulLogin()
    {
        $admin = adminLogin($this->conn, 'test@example.com', '12345');
        $this->assertIsArray($admin);
        $this->assertEquals('TestAdmin', $admin['username']);
    }

    public function testWrongPassword()
    {
        $admin = adminLogin($this->conn, 'test@example.com', 'wrongpass');
        $this->assertNull($admin);
    }

    public function testNonExistentEmail()
    {
        $admin = adminLogin($this->conn, 'notfound@example.com', '12345');
        $this->assertNull($admin);
    }
}
