<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <benoit.maziere@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Processor;

use Morocron\Cron\CronTabDefinition;
use Morocron\Exception\SortProcessorException;

/**
 * Class SortedCronTabProcessor
 * @package Morocron\Processor
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class SortedCronTabProcessor
{
    const FREQUENCY_STRATEGY = 0;

    const PERIODIC_STRATEGY = 1;

    const ALPHABETIC_STRATEGY = 2;

    /**
     * Sort
     *
     * @param CronTabDefinition $cronTabDefinition
     * @param int $strategy
     *
     * @throws \Morocron\Exception\SortProcessorException
     *
     * @return CronTabDefinition
     */
    public static function sort(CronTabDefinition $cronTabDefinition, $strategy = self::FREQUENCY_STRATEGY)
    {
        switch ($strategy) {
            case (self::FREQUENCY_STRATEGY) :
                $newCronTabDefinition = self::frequencySort($cronTabDefinition);
                break;
            default:
                throw SortProcessorException::invalidStrategy();
        }

        return $newCronTabDefinition;
    }

    /**
     * Frequency sort.
     *
     * Sort a cron tab definition by frequency.
     *
     * @param CronTabDefinition $cronTabDefinition
     *
     * @return CronTabDefinition
     */
    public static function frequencySort(CronTabDefinition $cronTabDefinition)
    {

    }
}