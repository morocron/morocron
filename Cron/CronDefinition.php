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
    /** @var CronExpression $definition */
    protected $definition;

    /** @var string $command */
    protected $command;

    /** @var string $periodicalRange */
    protected $periodicalRange;

    /** @var int $period */
    protected $period;

    /** @var int $offset */
    protected $offset = 0;

    /**
     * Constructor.
     *
     * @param CronExpression $definition
     * @param string $command
     * @param null $periodicalRange
     * @param null $period
     */
    public function __construct(CronExpression $definition, $command, $periodicalRange, $period)
    {
        $this->definition = $definition;
        $this->command = $command;
        $this->periodicalRange = $periodicalRange;
        $this->period = (int) $period;
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
     * Get PeriodRange.
     *
     * @return string
     */
    public function getPeriodicalRange()
    {
        return $this->periodicalRange;
    }

    /**
     * Set PeriodRange.
     *
     * @param string $periodicalRange
     * @return $this
     */
    public function setPeriodicalRange($periodicalRange)
    {
        $this->periodicalRange = $periodicalRange;

        return $this;
    }

    /**
     * Get Period.
     *
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set Period.
     *
     * @param int $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get Offset.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set Offset.
     *
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

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
}
