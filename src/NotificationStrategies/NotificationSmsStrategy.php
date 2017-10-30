<?php
namespace TestProject\NotificationStrategies;

use SMSApi\Api\SmsFactory;
use SMSApi\Client;
use SMSApi\Exception\SmsapiException;
use TestProject\Exceptions\NotificationException;
use TestProject\UserModel;

/**
 * Class NotificationSmsStrategy
 */
class NotificationSmsStrategy extends NotificationStrategyAbstract
{
    /**
     * @var SmsFactory
     */
    private $smsApi;

    /**
     * NotificationSmsStrategy constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $client = Client::createFromToken($this->getConfigValue('strategies_config.sms.token'));

        $this->smsApi = new SmsFactory();
        $this->smsApi->setClient($client);
    }

    /**
     * @param UserModel $user
     *
     * @return bool
     */
    public function canBeUsed(UserModel $user): bool
    {
        return !empty($user->getPhone());
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
        try {
            $actionSend = $this->getSmsApi()->actionSend();

            $actionSend->setSender($this->getConfigValue('strategies_config.sms.sender'));
            $actionSend->setTo($user->getPhone());
            $actionSend->setText($message);

            $actionSend->execute();
        } catch (SmsapiException $ex) {
            throw new NotificationException($ex->getMessage());
        }

        return true;
    }

    /**
     * @return SmsFactory
     */
    public function getSmsApi(): SmsFactory
    {
        return $this->smsApi;
    }
}
