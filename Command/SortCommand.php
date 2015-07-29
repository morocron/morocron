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

namespace Morocron\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Morocron\Generator\CronTabGenerator;

/**
 * Class Sort Command
 *
 * @package Morocron\Command
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class SortCommand extends Command
{
    /** @var string $source */
    protected $source;

    /** @var string $destination */
    protected $destination;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('sort')
            ->setDescription('Order the tasks execution of a cronTab file')
            ->addArgument('source', InputArgument::REQUIRED, 'The original cron tab file.');
    }

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @throws \Morocron\Exception\FileException
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->source = (string) $input->getArgument('source');
        $this->destination = sprintf("%s-reordered", $this->source);
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|integer null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cronTabGenerator = new CronTabGenerator();

        return $cronTabGenerator->createSortedCronTab(
            $this->source,
            $this->destination
        );
    }
}

