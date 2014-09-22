<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <ldf-b.maziere@lagardere-active.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Parser;

use Morocron\Exception\FileException;
use Symfony\Component\Finder\Finder;

/**
 * Class CronTabParser
 * @package Morocron\Parser
 * @author Benoit Maziere <ldf-b.maziere@lagardere-active.com>
 */
class CronTabParser
{
    /**
     * Valid And Periodic Tasks
     *
     * @var array
     */
    protected $validAndPeriodicTasks;

    /**
     * Valid And Non Periodic Tasks
     * @var array
     */
    protected $validAndNonPeriodicTasks;

    /**
     * Unreadable Tasks
     * @var array
     */
    protected $unreadableTasks;

    /**
     * @return array
     */
    public function getValidAndNonPeriodicTasks()
    {
        return $this->validAndNonPeriodicTasks;
    }

    /**
     * @return array
     */
    public function getValidAndPeriodicTasks()
    {
        return $this->validAndPeriodicTasks;
    }

    /**
     * @return array
     */
    public function getUnreadableTasks()
    {
        return $this->unreadableTasks;
    }

    /**
     * Get data from Fixtures directory
     *
     * @param string $cronTabFilePath
     * @throws \Exception
     * @return array
     */
    public function computeData($cronTabFilePath)
    {
        if (!file_exists($cronTabFilePath)) {
            throw FileException::notFoundException($cronTabFilePath);
        }

        $data = file($cronTabFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $this->setTasks($data);
    }

    /**
     * Set Tasks
     *
     * @param array $data
     *
     * @return $this
     */
    protected function setTasks(array $data)
    {
        $index = 0;
        foreach ($data as $task) {
            list($isReadable, $isPeriodic, $taskTimeDefinition, $taskDefinition) = $this->getCommand($task);
            if ($isReadable && $isPeriodic) {
                $this->validAndPeriodicTasks[] = $this->fillInCronDefinition($index, $taskTimeDefinition, $taskDefinition);
            } elseif ($isReadable && !$isPeriodic) {
                $this->validAndNonPeriodicTasks[] = $this->fillInCronDefinition($index, $taskTimeDefinition, $taskDefinition);
            } elseif (!$isReadable && trim($task) != '') {
                $this->unreadableTasks[] = $this->fillInCronDefinition($index, $taskTimeDefinition, $taskDefinition);
            }
            $index++;
        }

        return $this;
    }

    /**
     * Fill In Cron Definition
     *
     * @param int $index
     * @param string $taskTimeDefinition
     * @param string $taskDefinition
     *
     * @return array
     */
    protected function fillInCronDefinition($index, $taskTimeDefinition, $taskDefinition)
    {
        return array(
            'name' => 'Task ' . $index,
            'cronTimeDefinition' => trim($taskTimeDefinition),
            'command' => trim($taskDefinition),
        );
    }

    /**
     * Is Periodic
     *
     * @param string $taskTimeDefinition
     *
     * @return bool
     */
    protected function isPeriodic($taskTimeDefinition)
    {
        if (strpos($taskTimeDefinition, ',') || strpos($taskTimeDefinition, '-')) {
            return false;
        }

        return true;
    }

    /**
     * Get Command
     *
     * @param string $task
     *
     * @return array
     */
    protected function getCommand($task)
    {
        $task = trim($task);
        if ('@' === substr($task, 0, 1)) {
            $taskTimeDefinition = explode(' ', $task, 2);
            return array(
                (count($taskTimeDefinition) == 2 and $this->getSpecialCronTimeDefinition($taskTimeDefinition[0])) ? $taskTimeDefinition[1] : false,
                $this->isPeriodic($taskTimeDefinition[0]),
                $this->getSpecialCronTimeDefinition($taskTimeDefinition[0]),
                $taskTimeDefinition[1],

            );
        } else {
            $taskTimeDefinition = explode(' ', $task, 6);
            return array(
                (count($taskTimeDefinition) > 5) ? $taskTimeDefinition[5] : false,
                $this->isPeriodic($this->getStandardCronTimeDefinition($taskTimeDefinition)),
                $this->getStandardCronTimeDefinition($taskTimeDefinition),
                $taskTimeDefinition[5],
            );
        }
    }

    /**
     * Get Standard Cron Time Definition
     *
     * @param array $taskTimeDefinition
     *
     * @return string
     */
    protected function getStandardCronTimeDefinition(array $taskTimeDefinition)
    {
        return implode(' ', array_slice($taskTimeDefinition, 0, 5));
    }

    /**
     * Get Special CronTime Definition
     *
     * @param string $taskTimeDefinition
     *
     * @return bool|string
     */
    protected function getSpecialCronTimeDefinition($taskTimeDefinition)
    {
        $definition = null;
        switch (strtolower($taskTimeDefinition)) {
            case '@reboot':
                $definition = 'x x x x x';
                break;
            case '@yearly':
            case '@annually':
                $definition = '0 0 1 1 *';
                break;
            case '@monthly':
                $definition = '0 0 1 * *';
                break;
            case '@weekly':
                $definition = '0 0 * * 0';
                break;
            case '@daily':
            case '@midnight':
                $definition = '0 0 * * *';
                break;
            case '@hourly':
                $definition = '0 * * * *';
                break;
            default:
                $definition = false;
        }

        return $definition;
    }
}