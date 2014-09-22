<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <ldf-b.maziere@lagardere-active.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Generator;

/**
 * Class Cron Tab Generator
 * @package Morocron\Generator
 * @author Abdoul N'Diaye <wn-a.ndiaye@lagardere-active.com>
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

    }
}