<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <benoit.maziere@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Exception;

/**
 * Class Sort Processor Exception
 *
 * @package Morocron\Exception
 * @author Abdoul N'Diaye <wn-a.ndiaye@lagardere-active.com>
 */
class SortProcessorException extends \RuntimeException
{
    /**
     * Invalid Strategy.
     *
     * @return SortProcessorException
     */
    public static function invalidStrategy()
    {
        return new self('The Sorted cron tab processor strategy is invalid.');
    }
}