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
        return $cronDefinitions;
    }
}
