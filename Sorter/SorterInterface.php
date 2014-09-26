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

namespace Morocron\Sorter;

use Symfony\Component\Console\Output\OutputInterface;
use Morocron\Cron\CronTabDefinition;

/**
 * Sorter Interface
 *
 * @package Morocron\Sorter
 * @author Abdoul N'Diaye <wn-a.ndiaye@lagardere-active.com>
 */
interface SorterInterface
{
    public function sort(OutputInterface $output, CronTabDefinition $cronTabDefinition);
}