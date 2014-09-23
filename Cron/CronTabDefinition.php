<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <benoit.maziere@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Cron;

/**
 * Class Cron Tab Definition
 *
 * Representation of a cron tab.
 *
 * @package Morocron\Cron
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class CronTabDefinition
{
    /**
     * Periodic cron definitions.
     *
     * @var array
     */
    protected $periodicCronDefinitions;

    /**
     * Non Periodic cron definitions.
     *
     * @var array
     */
    protected $nonPeriodicCronDefinitions;

    /**
     * Unreadable cron definitions.
     *
     * @var array
     */
    protected $unreadableCronDefinitions;

    /**
     * Get Periodic Cron Definitions.
     *
     * @return array
     */
    public function getPeriodicCronDefinitions()
    {
        return $this->periodicCronDefinitions;
    }

    /**
     * Set Periodic Cron Definitions.
     *
     * @param array $periodicCronDefinitions
     *
     * @return $this
     */
    public function setPeriodicCronDefinitions(array $periodicCronDefinitions)
    {
        $this->periodicCronDefinitions = $periodicCronDefinitions;

        return $this;
    }

    /**
     * Add Periodic Cron Definition.
     *
     * @param CronDefinition $periodicCronDefinition
     *
     * @return $this
     */
    public function addPeriodicCronDefinition(CronDefinition $periodicCronDefinition)
    {
        $this->periodicCronDefinitions[] = $periodicCronDefinition;

        return $this;
    }

    /**
     * Get Non Periodic Cron Definitions
     *
     * @return array
     */
    public function getNonPeriodicCronDefinitions()
    {
        return $this->nonPeriodicCronDefinitions;
    }

    /**
     * Set Non Periodic Cron Definitions
     *
     * @param array $nonPeriodicCronDefinitions
     *
     * @return $this
     */
    public function setNonPeriodicCronDefinitions(array $nonPeriodicCronDefinitions)
    {
        $this->nonPeriodicCronDefinitions = $nonPeriodicCronDefinitions;

        return $this;
    }

    /**
     * Add Non Periodic Cron Definition.
     *
     * @param CronDefinition $nonPeriodicCronDefinition
     *
     * @return $this
     */
    public function addNonPeriodicCronDefinition(CronDefinition $nonPeriodicCronDefinition)
    {
        $this->nonPeriodicCronDefinitions[] = $nonPeriodicCronDefinition;

        return $this;
    }

    /**
     * Get Unreadable Cron Definitions.
     *
     * @return array
     */
    public function getUnreadableCronDefinitions()
    {
        return $this->unreadableCronDefinitions;
    }

    /**
     * Set Unreadable Cron Definitions.
     *
     * @param array $unreadableCronDefinitions
     *
     * @return $this
     */
    public function setUnreadableCronDefinitions(array $unreadableCronDefinitions)
    {
        $this->unreadableCronDefinitions = $unreadableCronDefinitions;

        return $this;
    }

    /**
     * Add Unreadable Cron Definition.
     *
     * @param CronDefinition $unreadableCronDefinition
     *
     * @return $this
     */
    public function addUnreadableCronDefinition(CronDefinition $unreadableCronDefinition)
    {
        $this->unreadableCronDefinitions[] = $unreadableCronDefinition;

        return $this;
    }

    /**
     * Convert the cron definition to string.
     *
     * @return string
     */
    public function convertToString()
    {
        $properties = array('periodicCronDefinitions', 'nonPeriodicCronDefinitions', 'unreadableCronDefinitions');

        $cronTab = '';

        foreach ($properties as $currentProperty) {
            /**
             * @var CronDefinition $cronDefinition
             */
            $methodName = 'get' . ucfirst($currentProperty);
            if (is_callable(array($this, $methodName))) {
                $cronTab .= sprintf("%s\n\n", $currentProperty);
                foreach ((array) $this->{$currentProperty} as $cronDefinition) {
                    $cronTab .= sprintf("%s\n", $cronDefinition->convertToString());
                }
            }
        }

        return $cronTab;
    }
}