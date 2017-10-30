<?php
namespace TestProject\NotificationStrategies;

use Noodlehaus\Config;
use TestProject\Exceptions\NotificationException;
use TestProject\UserModel;

/**
 * Class NotificationStrategyAbstract
 */
abstract class NotificationStrategyAbstract implements NotificationStrategyInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * NotificationStrategyAbstract constructor.
     */
    public function __construct()
    {
        $this->config = Config::load('../data/conf/config.json');
    }

    /**
     * @param UserModel $user
     *
     * @return bool
     */
    public function canBeUsed(UserModel $user): bool
    {
        return false;
    }

    /**
     * @param UserModel $user
     * @param string $message
     *
     * @throws NotificationException
     *
     * @return bool
     */
    public function sendNotification(UserModel $user, string $message): bool
    {
        return false;
    }

    public function getConfigValue(string $data, $default = null)
    {
        return $this->config->get($data, $default);
    }
}
