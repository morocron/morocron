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


 /**
 * Class DefinitionPart
 *
 * @author Sylvain Rascar <wn-s.rascar@lagardere-active.com>
 *
 */
class DefinitionPart 
{
    /**
     * @var string $stringValue
     */
    protected $stringValue;

    /**
     * @var boolean $isRegular
     */
    protected $isRegular;

    /**
     * start value in minutes
     *
     * @var int $start
     */
    protected $start;

    /**
     * stop value in minutes
     *
     * @var int $phaseShift
     */
    protected $stop;

    /**
     * filled only if constant step is set
     * which means $isRegular == true
     *
     * @var int $step
     */
    protected $step;

    /**
     * filled only if specific minutes are set
     * which means $isRegular == false
     *
     * @var array $step
     */
    protected $hits;

    /**
     * @param null|string $stringValue
     */
    public function __construct($stringValue = null)
    {
        if (!is_null($stringValue)) {
            $this->setStringValue($stringValue);
        }
    }


    /**
     * @param string $stringValue
     * @return $this
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }

    /**
     * @param boolean $isRegular
     * @return $this
     */
    public function setIsRegular($isRegular)
    {
        $this->isRegular = $isRegular;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRegular()
    {
        return $this->isRegular;
    }

    /**
     * @param array $hits
     * @return $this
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * @return array
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * @param int $start
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $stop
     * @return $this
     */
    public function setStop($stop)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * @return int
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * @param int $step
     * @return $this
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        if (is_null($this->step)) {
            return 1;
        }

        return $this->step;
    }


    /**
     * set string value according to period values set
     * in current DefinitionPart instance
     *
     * @throws \LogicException
     * @return $this
     */
    public function computeStringValue()
    {
        if (!(is_array($this->getHits()) && $this->getIsRegular() == false) && !($this->getStep() && $this->getIsRegular() == true)) {
            throw new \LogicException("String value can't be computed. Rules are:\n
            Hits must be an array and isRegular is false.\n
            or\n
            Step must be an int and isRegular is true.");
        }

        if ($this->getIsRegular()) {
            if ($this->getStep() == 1) {
                if (is_null($this->start) && is_null($this->stop)) {
                    $this->setStringValue('*');
                } else {
                    $this->setStringValue(sprintf('%d-%d', $this->getStart(), $this->getStop()));
                }
            } else {
                if (is_null($this->start) && is_null($this->stop)) {
                    $this->setStringValue(sprintf('*/%d', $this->getStep()));
                } else {
                    $this->setStringValue(sprintf('%d-%d/%d', $this->getStart(), $this->getStop(), $this->getStep()));
                }
            }
        } else {
            $this->setStringValue(implode(',', $this->getHits()));
        }

        return $this;
    }


    /**
     * set period according to string value set
     * in current DefinitionPart instance
     *
     * @throws \LogicException
     * @return $this
     */
    public function computePeriodValues()
    {
        if (!$this->getStringValue()) {
            throw new \LogicException('String value must be defined to compute period values');
        }
        $matches = array();
        switch (true)
        {
            case $this->getStringValue() == '*':
                $this->initAsPeriodic(1);
                break;
            case preg_match('#^\*\/([\d]+)$#', $this->getStringValue(), $matches):
                $this->initAsPeriodic($matches[1]);
                break;
            case preg_match('#^([\d]+)\-([\d]+)$#', $this->getStringValue(), $matches):
                $this->initAsPeriodic(1, $matches[2], $matches[1]);
                break;
            case preg_match('#^([\d]+)\-([\d]+)\/([\d]+)$#', $this->getStringValue(), $matches):
                $this->initAsPeriodic($matches[3], $matches[2], $matches[1]);
                break;
            case preg_match('#^([\d]+)(,[\d]+)*$#', $this->getStringValue(), $matches):
                $this->initAsNonPeriodic(explode(',', $this->getStringValue()));
                break;
        }

        return $this;
    }

    /**
     * @param int      $step
     * @param null|int $start
     * @param null|int $stop
     * @return $this
     */
    public function initAsPeriodic($step, $start = null, $stop = null)
    {
        $this->setIsRegular(true);
        $this->setStart($start);
        $this->setStop($stop);
        $this->setStep($step);

        return $this;
    }

    /**
     * @param array $hits
     *
     * @return $this
     */
    public function initAsNonPeriodic(array $hits)
    {
        $this->setIsRegular(false);
        $this->setHits($hits);

        return $this;
    }
}