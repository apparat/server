<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Ports
 * @author      Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright   Copyright © 2016 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2016 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Apparat\Server\Ports\Action;

use Apparat\Server\Ports\Types\ObjectRoute;

/**
 * Abstract action
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
abstract class AbstractSelectorAction extends AbstractAction implements SelectorActionInterface
{
    /**
     * Test whether the date selectors aren't empty
     *
     * @param array $attributes Attributes
     * @param int $maxDateTime Maximum date selectors
     * @return bool Date selectors are not
     */
    protected static function notEmptyDateSelector(array $attributes, $maxDateTime)
    {
        $dateSelectors = array_slice(
            [
                ObjectRoute::YEAR_STR,
                ObjectRoute::MONTH_STR,
                ObjectRoute::DAY_STR,
                ObjectRoute::HOUR_STR,
                ObjectRoute::MINUTE_STR,
                ObjectRoute::SECOND_STR,
            ],
            0,
            min($maxDateTime, intval(getenv('OBJECT_DATE_PRECISION')))
        );
        foreach ($dateSelectors as $dateSelector) {
            if (empty($attributes[$dateSelector])) {
                return false;
            }
        }
        return true;
    }
}
