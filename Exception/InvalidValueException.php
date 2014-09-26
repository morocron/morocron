<?php
/*
 * This file is part of the morocron project.
 *
 * (c) morocron
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Exception;


 /**
 * Class InvalidValueException
 *
 * @author Sylvain Rascar <wn-s.rascar@lagardere-active.com>
 *
 */
class InvalidValueException extends \InvalidArgumentException
{
    /**
     * max value reach.
     *
     * @param $propertyName
     * @param $max
     * @param $value
     *
     * @return InvalidValueException
     */
    public static function maxValueReach($propertyName, $max, $value)
    {
        return new self(sprintf('%s value cannot exceed max value of %d. %d given.', $propertyName, $max, $value));
    }
}