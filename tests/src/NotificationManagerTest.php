<?php

use Gears\ClassFinder;
use PHPUnit\Framework\TestCase;
use SMSApi\Api\SmsFactory;
use SMSApi\Client;
use TestProject\NotificationManager;
use TestProject\NotificationStrategies\NotificationEmailStrategy;
use TestProject\NotificationStrategies\NotificationSmsStrategy;

/**
 * Class NotificationManagerTest
 */
class NotificationManagerTest extends TestCase
{
    public function testGetAvailableStrategies()
    {
        $mockObject = $this->getMockBuilder(NotificationManager::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getAvailableStrategies'])
            ->getMock();

        $composer = require '../vendor/autoload.php';

        $finder = new ClassFinder($composer);
        $mockObject->method('getClassFinder')
            ->willReturn($finder);

        $result = $mockObject->getAvailableStrategies();

        $this->assertNotEmpty($result);
        foreach ($result as $strategy) {
            $this->assertRegExp(
                '/TestProject\\\\NotificationStrategies\\\\Notification([A-Za-z]+)Strategy/',
                $strategy
            );
        }
    }

    public function testLimitStrategies()
    {
        $onlyAcceptableStrategies = [
            'TestProject\NotificationStrategies\NotificationPigeonStrategy',
            NotificationSmsStrategy::class,
        ];

        $availableStrategies = [
            NotificationSmsStrategy::class,
            NotificationEmailStrategy::class,
        ];

        $limitedStrategies = [
            NotificationSmsStrategy::class,
        ];

        $mockObject = $this->getMockBuilder(NotificationManager::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['limitStrategies'])
            ->getMock();

        $mockObject->expects($this->once())
            ->method('getStrategies')
            ->willReturn($availableStrategies);

        $mockObject->expects($this->once())
            ->method('setLimitedStrategies')
            ->with($this->equalTo($limitedStrategies))
            ->willReturn($mockObject);

        $result = $mockObject->limitStrategies($onlyAcceptableStrategies);

        $this->assertInstanceOf(NotificationManager::class, $result);
    }
}
