<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <ldf-b.maziere@lagardere-active.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Cron;

use Cron\CronExpression;

/**
 * Class CronDefinition
 * @package Morocron\Cron
 * @author Abdoul N'Diaye <wn-a.ndiaye@lagardere-active.com>
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