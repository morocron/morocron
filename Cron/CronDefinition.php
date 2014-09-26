<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <benoit.maziere@gmail.com>
 * (c) Abdoul N'Diaye <abdoul.nd@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Cron;

use Cron\CronExpression;

/**
 * Class Cron Definition
 *
 * Representation of a cron task.
 *
 * @package Morocron\Cron
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class CronDefinition
{
    /**
     * Cron Definition.
     *
     * @var CronExpression
     */
    protected $definition;

    /**
     * Command.
     *
     * @var string
     */
    protected $command;

    /**
     * @var boolean $isPeriodic
     */
    protected $isPeriodic;

    /**
     * period value in minutes
     *
     * @var int $period
     */
    protected $period;

    /**
     * phaseShift value in minutes
     *
     * @var int $phaseShift
     */
    protected $phaseShift;


    /**
     * duration of the periodic behavior in minutes
     *
     * @var int $step
     */
    protected $step;


    /**
     * Constructor.
     *
     * @param CronExpression $definition
     * @param string $command
     */
    public function __construct(CronExpression $definition, $command)
    {
        $this->definition = $definition;
        $this->command = $command;
    }

    /**
     * Get Definition.
     *
     * @return \Cron\CronExpression
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set Definition.
     *
     * @param \Cron\CronExpression $definition
     *
     * @return $this
     */
    public function setDefinition(CronExpression $definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get Command.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set Command.
     *
     * @param string $command
     *
     * @return $this
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Convert to String.
     *
     * @return string
     */
    public function convertToString()
    {
        return sprintf("%s    %s", $this->getDefinition()->getExpression(), $this->getCommand());
    }

    /**
     * @param boolean $isPeriodic
     */
    public function setIsPeriodic($isPeriodic)
    {
        $this->isPeriodic = $isPeriodic;
    }

    /**
     * @return boolean
     */
    public function getIsPeriodic()
    {
        return $this->isPeriodic;
    }

    /**
     * @param int $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param int $phaseShift
     */
    public function setPhaseShift($phaseShift)
    {
        $this->phaseShift = $phaseShift;
    }

    /**
     * @return int
     */
    public function getPhaseShift()
    {
        return $this->phaseShift;
    }

    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }


}
