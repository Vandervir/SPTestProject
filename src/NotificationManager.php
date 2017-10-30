<?php
namespace TestProject;

use TestProject\Exceptions\NotificationException;
use TestProject\NotificationStrategies\NotificationStrategyAbstract;

/**
 * Class NotificationManager
 */
class NotificationManager extends NotificationManagerAbstract
{
    /**
     * @param array $selectedStrategies
     *
     * @return NotificationManager
     */
    public function limitStrategies(array $selectedStrategies): self
    {
        $limitedStrategies = [];
        foreach ($this->getStrategies() as $strategy) {
            if (in_array($strategy, $selectedStrategies)) {
                $limitedStrategies[] = $strategy;
            }
        }
        $this->setLimitedStrategies($limitedStrategies);

        return $this;
    }

    /**
     * @param UserModel $user
     * @param string $message
     */
    public function handle(UserModel $user, string $message)
    {
        foreach ($this->getLimitedStrategies() as $strategyNamespace) {
            try {
                $strategy = $this->instantiateStrategy($strategyNamespace);

                if (!$strategy->canBeUsed($user)) {
                    continue;
                }

                $strategy->sendNotification($user, $message);
            } catch (NotificationException $ex) {
                $this->resolveErrors($ex);
            }
        }

        $this->setLimitedStrategies([]);
    }

    /**
     * @param NotificationException $exception
     */
    protected function resolveErrors(NotificationException $exception)
    {
        //Handle notification strategy error (log systems)
    }

    /**
     * @param string $strategyNamespace
     *
     * @return NotificationStrategyAbstract
     */
    protected function instantiateStrategy(string $strategyNamespace): NotificationStrategyAbstract
    {
        return new $strategyNamespace();
    }
}
