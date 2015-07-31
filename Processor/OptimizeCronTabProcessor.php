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

use Cron\CronExpression;
use Morocron\Cron\CronDefinition;
use Morocron\Cron\CronTabDefinition;

/**
 * Class Optimize Cron Tab Processor
 *
 * @package Morocron\Processor
 * @author Benoit Maziere <ldf-b.maziere@gmail.com>
 */
class OptimizeCronTabProcessor
{
    /**
     * @param CronTabDefinition $cronTabDefinition
     * @return CronTabDefinition
     */
    public function optimize(CronTabDefinition $cronTabDefinition)
    {
        $periodicCronOptimized = $this->optimizePeriodic($cronTabDefinition->getPeriodicCronDefinitions());

        $newCronTabDefinition = new CronTabDefinition();
        $newCronTabDefinition
            ->setPeriodicCronDefinitions($periodicCronOptimized)
            ->setNonPeriodicCronDefinitions($cronTabDefinition->getNonPeriodicCronDefinitions())
            ->setUnreadableCronDefinitions($cronTabDefinition->getUnreadableCronDefinitions());


        return $newCronTabDefinition;
    }

    /**
     * @param $periodicCronDefinitions
     * @return mixed
     */
    protected function optimizePeriodic($periodicCronDefinitions)
    {
        $countByPeriod = $this->getCountByPeriods($periodicCronDefinitions);
        $cronDefinitionSortedByPeriod = $this->indexByPeriod($periodicCronDefinitions);
        
        $newPeriodicCronTabDefinitions = $this->attributeDefaultOffset($countByPeriod, $cronDefinitionSortedByPeriod);

        return $newPeriodicCronTabDefinitions;
    }

    /**
     * @param $countByPeriod
     * @param $cronDefinitionSortedByPeriod
     * @return array
     */
    protected function attributeDefaultOffset($countByPeriod, $cronDefinitionSortedByPeriod)
    {
        $newPeriodicCronTabDefinitions = array();

        foreach ($countByPeriod as $period => $count) {
            $currentPeriodCronDefinitions = $cronDefinitionSortedByPeriod[$period];
            foreach ($currentPeriodCronDefinitions as $index => $currentPeriodCronDefinition) {
                /** @var CronDefinition $currentPeriodCronDefinition */
                $currentPeriodCronDefinition->setOffset($index % $period);
                $newCurrentPeriodCronDefinition = $this->computeCrontPartDefinition($currentPeriodCronDefinition);
                $newPeriodicCronTabDefinitions[] = $newCurrentPeriodCronDefinition;
            }
        }

        return $newPeriodicCronTabDefinitions;
    }

    /**
     * @param CronDefinition $currentPeriodCronDefinition
     * @return CronExpression
     */
    protected function computeCrontPartDefinition(CronDefinition $currentPeriodCronDefinition)
    {
        if ($currentPeriodCronDefinition->getPeriodicalRange() === 'hour') {
            if (preg_match('/^(\*\/)?(\d+)$/', $currentPeriodCronDefinition->getDefinition()->getExpression(0), $matches)) {
                $newCronExpression = $currentPeriodCronDefinition->getDefinition()->setPart(0, sprintf('%d-59/%d', $currentPeriodCronDefinition->getOffset(), $currentPeriodCronDefinition->getPeriod()));
                $currentPeriodCronDefinition->setDefinition($newCronExpression);
            }
        }

        return $currentPeriodCronDefinition;
    }

    /**
     * @param $periodicCronDefinitions
     * @return array
     */
    protected function getCountByPeriods($periodicCronDefinitions)
    {
        $countByPeriod = array();
        foreach ($periodicCronDefinitions as $periodicCronDefinition) {
            /** @var CronDefinition $periodicCronDefinition */
            if (!in_array($periodicCronDefinition->getPeriod(), array_keys($countByPeriod))) {
                $countByPeriod[$periodicCronDefinition->getPeriod()] = 0;
            }
            $countByPeriod[$periodicCronDefinition->getPeriod()]++;
        }
        
        return $countByPeriod;
    }
    
    /**
     * @param $periodicCronDefinitions
     * @return array
     */
    protected function indexByPeriod($periodicCronDefinitions)
    {
        $sortedResults = array();
        foreach ($periodicCronDefinitions as $periodicCronDefinition) {
            /** @var CronDefinition $periodicCronDefinition */
            $sortedResults[$periodicCronDefinition->getPeriod()][] = $periodicCronDefinition;
        }

        return $sortedResults;
    }
}
