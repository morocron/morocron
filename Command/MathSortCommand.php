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
 * Class Math Sort Command
 * @package Morocron\Command
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class MathSortCommand extends Command
{
    /**
     * Path of the original cron tab file.
     *
     * @var string
     */
    protected $source;

    /**
     * Path of the new cron tab file.
     *
     * @var string
     */
    protected $destination;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('math-sort')
            ->setDescription('Order the tasks execution of a cronTab file')
            ->addArgument('source', InputArgument::REQUIRED, 'The original cron tab file.')
            ->addArgument('destination', InputArgument::REQUIRED, 'The new cron tab file that will be created by the command');
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
        $this->source = $input->getArgument('source');
        $this->destination = $input->getArgument('destination');
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
        return CronTabGenerator::createMathSortCronTab(
            (string)$input->getArgument('source'),
            (string)$input->getArgument('destination')
        );
    }
}

