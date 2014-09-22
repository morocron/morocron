<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <ldf-b.maziere@lagardere-active.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Application;

use Morocron\Command\SortCronCommand;
use Symfony\Component\Console\Application as AbstractApplication;

/**
 * Class MorocronApplication
 * @package Morocron\Application
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class MorocronApplication extends AbstractApplication
{
    public function __construct()
    {
        parent::__construct('morocron');

        $this->add(new SortCronCommand());
    }
}