<?php
/*
 * This file is part of the morocron project.
 *
 * (c) morocron
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Cron;

use Morocron\Exception\InvalidValueException;
 /**
 * Trait MaxValidationRules
 * Those rules apply to attributes that respect a regular
 * cycle (ie: minutes and hours)
 *
 * @author Sylvain Rascar <wn-s.rascar@lagardere-active.com>
 *
 */
trait MaxValidationRules
{


    /**
     * @param int $stop
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setStop($stop)
    {
        if ($stop > self::MAX_VALUE) {
            throw InvalidValueException::maxValueReach('Stop', self::MAX_VALUE, $stop);
        }
        $this->stop = $stop;

        return $this;
    }


    /**
     * @param int $start
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setStart($start)
    {
        if ($start > self::MAX_VALUE) {
            throw  InvalidValueException::maxValueReach('Start', self::MAX_VALUE, $start);
        }
        $this->start = $start;

        return $this;
    }

    /**
     * @param int $step
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setStep($step)
    {
        if ($step > self::MAX_VALUE) {
            throw  InvalidValueException::maxValueReach('Step', self::MAX_VALUE, $step);
        }
        $this->step = $step;

        return $this;
    }

}