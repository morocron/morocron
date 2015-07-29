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

        return $periodicCronDefinitions;
    }

}
