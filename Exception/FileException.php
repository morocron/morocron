<?php

/**
 * This file is part of the Morocron project.
 *
 * (c) Benoit Maziere <ldf-b.maziere@lagardere-active.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Exception;

/**
 * Class FileException
 * @package Morocron\Exception
 * @author Abdoul N'Diaye <wn-a.ndiaye@lagardere-active.com>
 */
class FileException extends \RuntimeException
{
    /**
     * File not found exception.
     *
     * @static
     *
     * @param string $path
     *
     * @return FileException
     */
    public static function notFoundException($path)
    {
        return new self(sprintf('The file at path %s does not exist.', $path));
    }
}