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

            list($isReadable, $isPeriodic, $taskTimeDefinition, $taskDefinition, $periodicalRange, $period) = $this->getCommand($task);

            $method = null;

            if ($isReadable && $isPeriodic) {
                $method = 'addPeriodicCronDefinition';
            } elseif ($isReadable && !$isPeriodic) {
                $method = 'addNonPeriodicCronDefinition';
            } elseif (!$isReadable && trim($task) != '') {
                $method = 'addUnreadableCronDefinition';
            }

            if (!is_null($method)) {
                $cronTabDefinition->{$method}($this->fillInCronDefinition($taskTimeDefinition, $taskDefinition, $periodicalRange, $period));
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
     * @param $periodicalRange
     * @param $period
     *
     * @return CronDefinition
     */
    protected function fillInCronDefinition($taskTimeDefinition, $taskDefinition, $periodicalRange, $period)
    {
        return new CronDefinition(
            CronExpression::factory($taskTimeDefinition),
            trim($taskDefinition),
            $periodicalRange,
            $period
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
        $taskTimeDefinitionExploded = explode(' ', $taskTimeDefinition);

        if (strpos($taskTimeDefinition, ',')
            || strpos($taskTimeDefinition, '-')
            || (preg_match('/[0-9\-,]+/', $taskTimeDefinitionExploded[2]) && preg_match('/[0-9\-,]+/', $taskTimeDefinitionExploded[4]))) {
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
            $isPeriodic = $this->isPeriodic($taskTimeDefinition[0]);
            $specialCronTimeDefinition = $this->getSpecialCronTimeDefinition($taskTimeDefinition[0]);
            $taskTimeDefinitionExploded = explode(' ', $specialCronTimeDefinition);

            $cronTimeDefinition = array(
                (count($taskTimeDefinition) === 2 && $specialCronTimeDefinition) ? $taskTimeDefinition[1] : false,
                $isPeriodic,
                $specialCronTimeDefinition,
                $taskTimeDefinition[1],
            );
        } else {
            $taskTimeDefinition = explode(' ', $task, 6);
            $standardCronTimeDefinition = $this->getStandardCronTimeDefinition($taskTimeDefinition);
            $taskTimeDefinitionExploded = explode(' ', $standardCronTimeDefinition);
            $isPeriodic = $this->isPeriodic($standardCronTimeDefinition);

            $cronTimeDefinition = array(
                (count($taskTimeDefinition) > 5) ? $taskTimeDefinition[5] : false,
                $isPeriodic,
                $standardCronTimeDefinition,
                $taskTimeDefinition[5],
            );
        }
        $periodicalRange = $this->getPeriodicalRange($taskTimeDefinitionExploded);

        $cronTimeDefinition = array_merge($cronTimeDefinition, array(
            $isPeriodic ? $periodicalRange : null,
            ($isPeriodic && $periodicalRange) ? $this->getPeriod($taskTimeDefinition, $periodicalRange) : null,
        ));

        return $cronTimeDefinition;
    }

    /**
     * Get Periodical Range from cron time expression
     *
     * @param array $taskTimeDefinition
     *
     * @return string|bool
     */
    protected function getPeriodicalRange(array $taskTimeDefinition)
    {
        if (count($taskTimeDefinition) === 5) {
            if ($taskTimeDefinition[4] === '*' && $taskTimeDefinition[3] === '*' && $taskTimeDefinition[2] === '*' && $taskTimeDefinition[1] === '*' && $taskTimeDefinition[0] === '*') {
                return 'minute';
            } elseif ($taskTimeDefinition[4] === '*' && $taskTimeDefinition[3] === '*' && $taskTimeDefinition[2] === '*' && $taskTimeDefinition[1] === '*') {
                return 'hour';
            } elseif ($taskTimeDefinition[4] === '*' && $taskTimeDefinition[3] === '*' && $taskTimeDefinition[2] === '*') {
                return 'day';
            } elseif ($taskTimeDefinition[4] === '*' && $taskTimeDefinition[3] === '*') {
                return 'month';
            } elseif ($taskTimeDefinition[4] === '*') {
                return 'week';
            }
        }
        
        return false;
    }

    /**
     * Get Period from cron time expression
     *
     * @param array $taskTimeDefinition
     * @param $periodicalRange
     *
     * @return int|null
     */
    protected function getPeriod(array $taskTimeDefinition, $periodicalRange)
    {
        switch ($periodicalRange) {
            case 'minute':
                return 1;
                break;
            case 'hour':
                if (preg_match('/^(\*\/)?(\d+)$/', $taskTimeDefinition[0], $matches)) {
                    if (isset($matches[1]) && $matches[1] === '*/') {
                        return $matches[2];
                    } else {
                        return 60;
                    }
                }
                break;
            case 'day':
                if (preg_match('/^(\*\/)?(\d+)$/', $taskTimeDefinition[1], $matches)) {
                    if (isset($matches[1]) && $matches[1] === '*/') {
                        return $matches[2] * 60;
                    } else {
                        return 60 * 24;
                    }
                }
                break;
            case 'week':
//                @todo
                return null;
                break;
            case 'month':
//                @todo
                return null;
                break;
            default:
                return null;
        }

        return null;
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
