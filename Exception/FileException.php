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

namespace Morocron\Exception;

/**
 * Class File Exception
 *
 * @package Morocron\Exception
 * @author Abdoul N'Diaye <abdoul.nd@gmail.com>
 */
class FileException extends \InvalidArgumentException
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

    /**
     * No Cron Task Exception
     *
     * @static
     *
     * @param string $path
     *
     * @return FileException
     */
    public static function noCronTaskException($path)
    {
        return new self(sprintf('The file at path %s is empty.', $path));
    }
}
