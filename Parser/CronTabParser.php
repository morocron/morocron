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

namespace Morocron\Parser;

use Cron\CronExpression;
use Morocron\Cron\CronDefinition;
use Morocron\Cron\CronTabDefinition;
use Morocron\Exception\FileException;

/**
 * Class CronTabParser
 * @package Morocron\Parser
 * @author Benoit Maziere <benoit.maziere@gmail.com>
 */
class CronTabParser
{
    /**
     * Get data from Fixtures directory
     *
     * @param string $cronTabFilePath
     *
     * @throws FileException
     *
     * @return CronTabDefinition
     */
    public function computeData($cronTabFilePath)
    {
        if (!file_exists($cronTabFilePath)) {
            throw FileException::notFoundException($cronTabFilePath);
        }

        $data = file($cronTabFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (is_null($data)) {
            throw FileException::noCronTaskException($cronTabFilePath);
        }

        return $this->generateCronTabDefinition($data);
    }

    /**
     * Set Tasks
     *
     * @param array $data
     *
     * @return CronTabDefinition
     */
    protected function generateCronTabDefinition(array $data)
    {
        $cronTabDefinition = new CronTabDefinition();
        foreach ($data as $task) {
            if (strpos(trim($task), '#') === 0) {
                continue;
            }

            list($isReadable, $isPeriodic, $taskTimeDefinition, $taskDefinition) = $this->getCommand($task);

            $method = null;

            if ($isReadable && $isPeriodic) {
                $method = 'addPeriodicCronDefinition';
            } elseif ($isReadable && !$isPeriodic) {
                $method = 'addNonPeriodicCronDefinition';
            } elseif (!$isReadable && trim($task) != '') {
                $method = 'addUnreadableCronDefinition';
            }

            if (!is_null($method)) {
                $cronTabDefinition->{$method}($this->fillInCronDefinition($taskTimeDefinition, $taskDefinition));
            } else {
                // @todo log or error
            }
        }

        return $cronTabDefinition;
    }

    /**
     * Fill In Cron Definition
     *
     * @param string $taskTimeDefinition
     * @param string $taskDefinition
     *
     * @return CronDefinition
     */
    protected function fillInCronDefinition($taskTimeDefinition, $taskDefinition)
    {
        return new CronDefinition(
            CronExpression::factory($taskTimeDefinition),
            trim($taskDefinition)
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
        if (strpos($task, '@') === 0) {
            $taskTimeDefinition = explode(' ', $task, 2);
            return array(
                (count($taskTimeDefinition) == 2 && $this->getSpecialCronTimeDefinition($taskTimeDefinition[0])) ? $taskTimeDefinition[1] : false,
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
