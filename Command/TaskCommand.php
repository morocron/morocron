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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Morocron\Generator\CronTabGenerator;

/**
 * Class Task Command
 * @package Morocron\Command
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class TaskCommand extends Command
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
            ->setName('execute-tasks')
            ->setDescription('Execute a task suites on a cron tab')
            ->addOption('source', 's', InputOption::VALUE_OPTIONAL, 'The original cron tab file.')
            ->addOption('input', 'i', InputOption::VALUE_REQUIRED, 'Get input content of a command and parse result as a cron tab', 'crontab -l')
            ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'The new cron tab file that will be created by the command')
            ->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Read configuration from morocron XML file.', 'morocron.xml.dist')            ->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Read configuration from morocron XML file.', 'morocron.xml.dist')
            ->addOption('tasks', 't', InputOption::VALUE_REQUIRED, 'The task suites that will be execute. Values are defined in configuration file', 'default')
        ;
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
    }
}

