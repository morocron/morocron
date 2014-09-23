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
}