<?php
namespace TestProject\NotificationStrategies;

use TestProject\Exceptions\NotificationException;
use TestProject\UserModel;

/**
 * Interface NotificationStrategyInterface
 */
interface NotificationStrategyInterface
{
    /**
     * @param UserModel $user
     *
     * @return bool
     */
    public function canBeUsed(UserModel $user): bool;

    /**
     * @param UserModel $user
     * @param string $message
     *
     * @throws NotificationException
     *
     * @return bool
     */
    public function sendNotification(UserModel $user, string $message): bool;
}
