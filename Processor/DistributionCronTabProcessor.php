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
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeZone;
use Morocron\Cron\CronDefinition;
use Morocron\Cron\CronTabDefinition;

/**
 * Class Distribution Cron Tab Processor
 *
 * @package Morocron\Processor
 * @author Benoit Maziere <ldf-b.maziere@gmail.com>
 */
class DistributionCronTabProcessor
{
    /**
     * @param CronTabDefinition $cronTabDefinition
     * @return CronTabDefinition
     */
    public function compute(CronTabDefinition $cronTabDefinition)
    {
        $cronDefinitions = array_merge(
            $cronTabDefinition->getPeriodicCronDefinitions(),
            $cronTabDefinition->getNonPeriodicCronDefinitions()
        );

        $cronTabDistribution = $this->computeDistribution($cronDefinitions);
        $cronTabDefinition
            ->setDistribution($cronTabDistribution);

        return $cronTabDefinition;
    }

    /**
     * @param $cronDefinitions
     * @return mixed
     */
    protected function computeDistribution(array $cronDefinitions)
    {
        $distribution = array();
        $period = $this->getPeriodDefinitionForDistribution();
        $oneMinuteCronCount = $this->getOneMinutePeriodCronCount($cronDefinitions);

        foreach ($period as $dt) {
            foreach ($cronDefinitions as $cronDefinition) {
                if ($cronDefinition->getPeriod() !== 1) {
                    /** @var CronDefinition $cronDefinition */
                    $cronExpression = $cronDefinition->getDefinition();
                    /** @var CronExpression $cronExpression */
                    if ($cronExpression->isDue($dt)) {
                        /** @var DateTime $dt */
                        $distribution[$dt->format('Y-m-d H:i')]++;
                    }
                }
            }
            $distribution[$dt->format('Y-m-d H:i')] += $oneMinuteCronCount;
        }

        return json_encode($distribution);
    }

    /**
     * @return DatePeriod
     */
    protected function getPeriodDefinitionForDistribution()
    {
        $today = date('Y-m-d');
        $begin = DateTime::createFromFormat(
            'Y-m-d H:i:s', $today . " 00:00:00",
            new DateTimeZone('Europe/paris')
        );
        $end = DateTime::createFromFormat(
            'Y-m-d H:i:s', $today . " 23:59:59",
            new DateTimeZone('Europe/paris')
        );

        $interval = DateInterval::createFromDateString('1 minute');

        return new DatePeriod($begin, $interval, $end);
    }

    /**
     * @param $cronDefinitions
     * @return int
     */
    protected function getOneMinutePeriodCronCount($cronDefinitions)
    {
        $oneMinuteCronCount = 0;
        foreach ($cronDefinitions as $cronDefinition) {
            /** @var CronDefinition $cronDefinition */
            if ($cronDefinition->getPeriod() === 1) {
                $oneMinuteCronCount++;
            }
        }

        return $oneMinuteCronCount;
    }
}
