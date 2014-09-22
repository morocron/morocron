<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <benoit.maziere@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Generator;

use Morocron\Parser\CronTabParser;

/**
 * Class Cron Tab Generator
 * @package Morocron\Generator
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class CronTabGenerator
{
    /**
     * Source.
     *
     * @var string
     */
    protected $source;

    /**
     * Destination.
     *
     * @var string
     */
    protected $destination;

    /**
     * Constructor.
     *
     * @param string $source
     * @param string $destination
     */
    public function __construct($source, $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * @param null $strategy
     */
    public function createSortedCronTab($strategy = null)
    {
        $cronTabParser = new CronTabParser();
        $cronTabParser->computeData($this->source);
        var_dump($cronTabParser->getValidAndPeriodicTasks());
    }
}