<?php
namespace TestProject\NotificationStrategies;

use Exception;
use Mailgun\Mailgun;
use TestProject\Exceptions\NotificationException;
use TestProject\UserModel;

/**
 * Class NotificationEmailStrategy
 */
class NotificationEmailStrategy extends NotificationStrategyAbstract
{
    /**
     * @var Mailgun
     */
    private $mailer;

    /**
     * NotificationEmailStrategy constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->mailer = Mailgun::create($this->getConfigValue('strategies_config.email.token'));
    }

    /**
     * @param UserModel $user
     *
     * @return bool
     */
    public function canBeUsed(UserModel $user): bool
    {
        return !empty($user->getEmail()) && $this->isValidEmail($user->getEmail());
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
            $this->getMailer()
                ->messages()
                ->send(
                    $this->getConfigValue('strategies_config.email.domain'),
                    [
                        'from' => $this->getConfigValue('strategies_config.email.from'),
                        'to' => $user->getEmail(),
                        'subject' => $this->getSubject($user),
                        'text' => $message,
                    ]
                );
        } catch (Exception $ex) {
            throw new NotificationException($ex->getMessage());
        }

        return true;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function isValidEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @return Mailgun
     */
    public function getMailer(): Mailgun
    {
        return $this->mailer;
    }

    /**
     * @param UserModel $user
     *
     * @return string
     */
    private function getSubject(UserModel $user)
    {
        return 'Notification for ' . $user->getName() . ' ' . $user->getSurname() . '(' . $user->getUsername() . ')';
    }
}
