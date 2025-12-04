<?php

use PHPUnit\Framework\TestCase;
require_once 'RegisterService.php';

class RegisterCustomerTest extends TestCase
{
    public function testSuccessfulRegistration()
    {
        $mockConn = $this->createMock(mysqli::class);
        $mockStmt = $this->createMock(mysqli_stmt::class);

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('bind_param')->willReturn(true);
        $mockStmt->method('execute')->willReturn(true);

        $service = new RegisterService($mockConn);
        $result = $service->registerCustomer("John", "john@example.com", "12345");

        $this->assertTrue($result);
    }

    public function testDuplicateEmail()
    {
        $mockConn = $this->createMock(mysqli::class);
        $mockStmt = $this->createMock(mysqli_stmt::class);

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('bind_param')->willReturn(true);
        $mockStmt->method('execute')->willReturn(false);

        // mock only our wrapper method
        $service = $this->getMockBuilder(RegisterService::class)
                        ->setConstructorArgs([$mockConn])
                        ->onlyMethods(['getStmtErrno'])
                        ->getMock();

        // simulate duplicate email error
        $service->method('getStmtErrno')->willReturn(1062);

        $result = $service->registerCustomer("John", "john@example.com", "12345");

        $this->assertSame("Email already exists", $result);
    }

    public function testInsertFails()
    {
        $mockConn = $this->createMock(mysqli::class);
        $mockStmt = $this->createMock(mysqli_stmt::class);

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('bind_param')->willReturn(true);
        $mockStmt->method('execute')->willReturn(false);

        $service = $this->getMockBuilder(RegisterService::class)
                        ->setConstructorArgs([$mockConn])
                        ->onlyMethods(['getStmtErrno'])
                        ->getMock();

        // non-duplicate error
        $service->method('getStmtErrno')->willReturn(9999);

        $result = $service->registerCustomer("John", "john@example.com", "12345");

        $this->assertSame("Registration failed", $result);
    }
}
