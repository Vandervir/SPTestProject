<?php

use PHPUnit\Framework\TestCase;
use TestProject\UserModel;

/**
 * Class UserModelTest
 */
class UserModelTest extends TestCase
{
    public function testLoadByIdSuccess()
    {
        $userId = 1;
        $path = '../data/user/user_' . $userId . '.json';

        $mockObject = $this->getMockBuilder(UserModel::class)
            ->setMethodsExcept(['loadById'])
            ->getMock();
        $mockObject->expects($this->once())
            ->method('getPath')
            ->willReturn($path);

        $user = $mockObject->loadById($userId);

        $this->assertInstanceOf(UserModel::class, $user);
    }

    public function testLoadByIdFailsNotFound()
    {
        $userId = 999;
        $path = '../data/user/user_' . $userId . '.json';

        $mockObject = $this->getMockBuilder(UserModel::class)
            ->setMethodsExcept(['loadById'])
            ->getMock();
        $mockObject->method('getPath')
            ->willReturn($path);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User not found');

        $mockObject->loadById($userId);
    }
}
