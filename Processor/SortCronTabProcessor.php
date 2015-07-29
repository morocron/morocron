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

namespace Morocron\Processor;

use Morocron\Cron\CronDefinition;
use Morocron\Cron\CronTabDefinition;
use Morocron\Exception\SortProcessorException;
use Morocron\Parser\CronTabParser;

/**
 * Class Sort Cron Tab Processor
 *
 * @package Morocron\Processor
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class SortCronTabProcessor
{
    /** @const FREQUENCY_STRATEGY */
    const FREQUENCY_STRATEGY = 0;

    /** @const PERIODIC_STRATEGY */
    const PERIODIC_STRATEGY = 1;

    /** @const ALPHABETIC_STRATEGY */
    const ALPHABETIC_STRATEGY = 2;

    /** @var array $periodicalRangeOrder */
    public static $periodicalRangeOrder = array(
        'minute',
        'hour',
        'day',
        'week',
        'month'
    );

    /**
     * Compute data and sort
     *
     * @param $source
     * @param $strategy
     *
     * @return CronTabDefinition
     */
    public function computeDataAndSort($source, $strategy)
    {
        $cronTabParser = new CronTabParser();
        $cronTabDefinition = $cronTabParser->computeData($source);

        return $this->sort($cronTabDefinition, $strategy);
    }

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
    protected function sort(CronTabDefinition $cronTabDefinition, $strategy = self::FREQUENCY_STRATEGY)
    {
        switch ($strategy) {
            case (self::FREQUENCY_STRATEGY) :
                $newCronTabDefinition = $this->frequencySort($cronTabDefinition);
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
     * @return \Morocron\Cron\CronTabDefinition
     */
    protected function frequencySort(CronTabDefinition $cronTabDefinition)
    {
        $periodicCronDefinitions = $cronTabDefinition->getPeriodicCronDefinitions();

        $start = new \DateTime();
        $end = clone($start);
        $end = $end->add(\DateInterval::createFromDateString('1 day'));
        $interval = \DateInterval::createFromDateString('1 minute');
        $period = new \DatePeriod($start, $interval, $end);
        $result = array();

        foreach ($period as $dt) {
            /** @var \DateTime $dt */
            $result[$dt->format('Y-m-d H:i')] = 0;
            /** @var CronDefinition $task */
            foreach ($periodicCronDefinitions as $task) {
                $result[$dt->format('Y-m-d H:i')] += $task->getDefinition()->isDue($dt);
            }
        }

        $periodicalSorted = $this->indexByPeriodicalRangeAndPeriod($periodicCronDefinitions);
        $cronTabDefinition->setPeriodicCronDefinitions($periodicalSorted);

        return $cronTabDefinition;
    }

    /**
     * @param CronDefinition $a
     * @param CronDefinition $b
     * @return int
     */
    protected function sortObjectsByPeriod($a, $b)
    {
        return ($a->getPeriod() == $b->getPeriod()) ? 0 : ($a->getPeriod() < $b->getPeriod()) ? -1 : 1;
    }

    /**
     * @param array $results
     *
     * @return array
     */
    protected function indexByPeriodicalRangeAndPeriod(array $results)
    {
        $sortedResults = array();

        /** @var CronDefinition $result */
        foreach ($results as $result) {
            $sortedResults[$result->getPeriodicalRange()][] = $result;
        }

        foreach ($sortedResults as &$sortedResult) {
            usort($sortedResult, function($a, $b) {
                /** @var CronDefinition $a */
                /** @var CronDefinition $b */
                return ($a->getPeriod() == $b->getPeriod()) ? 0 : (($a->getPeriod() < $b->getPeriod()) ? -1 : 1);
            });
        }

        $periodicalRangeSortedResults = self::sortArrayByArray($sortedResults, self::$periodicalRangeOrder);
        $processedResults = array();
        foreach ($periodicalRangeSortedResults as $periodicalRangeSortedResult) {
            $processedResults = array_merge($processedResults, $periodicalRangeSortedResult);
        }

        return $processedResults;
    }

    /**
     * @param array $toSort
     * @param array $sortByValuesAsKeys
     * @return array
     */
    protected static function sortArrayByArray(array $toSort, array $sortByValuesAsKeys)
    {
        $commonKeysInOrder = array_intersect_key(array_flip($sortByValuesAsKeys), $toSort);
        $commonKeysWithValue = array_intersect_key($toSort, $commonKeysInOrder);
        $sorted = array_merge($commonKeysInOrder, $commonKeysWithValue);

        return $sorted;
    }
}
