<?php
namespace TestProject;

use Gears\ClassFinder;

/**
 * Class NotificationManagerAbstract
 */
abstract class NotificationManagerAbstract
{
    /**
     * @var ClassFinder
     */
    protected $classFinder;

    /**
     * @var array
     */
    protected $strategies;

    /**
     * @var array
     */
    protected $limitedStrategies;

    /**
     * NotificationManagerAbstract constructor.
     *
     * @param ClassFinder $classFinder
     */
    public function __construct(ClassFinder $classFinder)
    {
        $this->classFinder = $classFinder;
        $this->strategies = $this->getAvailableStrategies();
    }

    /**
     * @return array
     */
    public function getAvailableStrategies(): array
    {
        return $this->getClassFinder()
            ->namespace('TestProject\\NotificationStrategies')
            ->search();
    }

    /**
     * @return ClassFinder
     */
    public function getClassFinder(): ClassFinder
    {
        return $this->classFinder;
    }

    /**
     * @return array
     */
    public function getLimitedStrategies(): array
    {
        if (empty($this->limitedStrategies)) {
            return $this->strategies;
        }

        return $this->limitedStrategies;
    }

    /**
     * @param array $limitedStrategies
     *
     * @return $this
     */
    public function setLimitedStrategies(array $limitedStrategies): self
    {
        $this->limitedStrategies = $limitedStrategies;

        return $this;
    }

    /**
     * @return array
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }
}
