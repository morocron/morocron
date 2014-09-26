<?php
/*
 * This file is part of the morocron project.
 *
 * (c) morocron
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Morocron\Parser;


 /**
 * Class DailyPeriodParser
 *
 * Parse or generate definition for a cron on a daily
 * base regardless of higher rules (month, year)
 *
 * @author Sylvain Rascar <wn-s.rascar@lagardere-active.com>
 *
 */
class DailyPeriodParser
{

    /**
     *
     * @param string $definition the cron definition(ex: 5,20 * * * *)
     *
     * @throws \LogicException
     * @return array
     */
    public static function getPeriodDefinitionArray($definition)
    {
        $definitionParts = explode(' ', trim($definition));
        if (count($definitionParts) !== 5) {
            throw new \LogicException(sprintf('Unable to split the cron definition "%s"'));
        }



        $periodDefinition = array();

        return $periodDefinition;
    }
}