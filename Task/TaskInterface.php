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

namespace Morocron\Task;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Morocron\Cron\CronTabDefinition;

/**
 * Task Interface
 *
 * @package Morocron\Sorter
 * @author Abdoul N'Diaye <wn-a.ndiaye@lagardere-active.com>
 */
interface TaskInterface
{
    /**
     * Execute a task on a cron tab definition
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param CronTabDefinition $cronTabDefinition
     *
     * @return CronTabDefinition
     */
    public function execute(InputInterface $input, OutputInterface $output, CronTabDefinition $cronTabDefinition);
}
