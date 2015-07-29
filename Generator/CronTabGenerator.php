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

namespace Morocron\Generator;

use Morocron\Cron\CronTabDefinition;
use Morocron\Processor\SortCronTabProcessor;

/**
 * Class Cron Tab Generator
 *
 * @package Morocron\Generator
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class CronTabGenerator
{
    /**
     * Create Sorted Cron Tab
     *
     * @param string $source
     * @param string $destination
     * @param int $strategy
     *
     * @return bool|int
     */
    public function createSortedCronTab($source, $destination, $strategy = SortCronTabProcessor::FREQUENCY_STRATEGY)
    {
        $SortCronTabProcessor = new SortCronTabProcessor();
        $newCronTabDefinition = $SortCronTabProcessor->computeDataAndSort($source, $strategy);

        return $this->generateCronTabFile($destination, $newCronTabDefinition);
    }

    /**
     * Generate Cron Tab File
     *
     * @param string $destination
     * @param CronTabDefinition $cronTabDefinition
     *
     * @return int|boolean
     */
    public function generateCronTabFile($destination, CronTabDefinition $cronTabDefinition)
    {
        return file_put_contents($destination, $cronTabDefinition->convertToString()) !== false ? true : false;
    }
}
