<?php
namespace TestProject\Cron;

use Gears\ClassFinder;
use TestProject\NotificationManager;
use TestProject\UserModel;

/**
 * Class NotificationConsumer
 */
class NotificationConsumer
{
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * NotificationConsumer constructor
     */
    public function __construct()
    {
        $composer = require '../vendor/autoload.php';
        $finder = new ClassFinder($composer);
        $this->notificationManager = new NotificationManager($finder);
        $this->userModel = new UserModel();
    }

    /**
     * @param string $message Message from queue
     *
     * @return bool
     */
    public function processQueue(string $message)
    {
        $message = json_decode($message);
        if (empty($message) || !is_object($message) || !property_exists($message, 'message')) {
            echo 'Invalid message in queue :' . PHP_EOL;

            return true;
        }

        try {
            $this->userModel->loadById($message->user_id);

            if (property_exists($message, 'strategies') && !empty($message->strategies)) {
                $this->notificationManager->limitStrategies($message->strategies);
            }

            $this->notificationManager->handle($this->userModel, $message->message);
        } catch (\Exception $ex) {
            //handle additional exceptions
        }

        return true;
    }
}
