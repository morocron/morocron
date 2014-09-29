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

use Morocron\Cron\MaxValidationRules;
 /**
 * Class HourDefinition
 *
 * @author Sylvain Rascar <wn-s.rascar@lagardere-active.com>
 *
 */
class HourDefinition extends DefinitionPart
{
    const MAX_VALUE = 23;

    use MaxValidationRules;
}