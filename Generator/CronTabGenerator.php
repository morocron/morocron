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
use Morocron\Parser\CronTabParser;
use Morocron\Processor\SortedCronTabProcessor;

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
     * @static
     *
     * @param string $source
     * @param string $destination
     * @param null $strategy
     *
     * @return bool|int
     */
    public static function createSortedCronTab($source, $destination, $strategy = SortedCronTabProcessor::FREQUENCY_STRATEGY)
    {
        $cronTabParser = new CronTabParser();
        $cronTabDefinition = $cronTabParser->computeData($source);
        $newCronTabDefinition = SortedCronTabProcessor::sort($cronTabDefinition, $strategy);

        return self::generateCronTabFile($destination, $newCronTabDefinition);
    }

    /**
     * Generate Cron Tab File
     *
     * @static
     *
     * @param string $destination
     * @param CronTabDefinition $cronTabDefinition
     *
     * @return int|boolean
     */
    public static function generateCronTabFile($destination, CronTabDefinition $cronTabDefinition)
    {
        return file_put_contents($destination, $cronTabDefinition->convertToString()) !== false ? true : false;
    }
}
