<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Ports
 * @author      Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright   Copyright © 2016 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
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

namespace Apparat\Server\Ports\Types;

/**
 * Default route types
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class DefaultRoute
{
    /**
     * Default route name / bit mapping
     *
     * @var array
     */
    protected static $nameBits = [
        self::YEAR_STR => self::YEAR,
        self::MONTH_STR => self::MONTH,
        self::DAY_STR => self::DAY,
        self::HOUR_STR => self::HOUR,
        self::MINUTE_STR => self::MINUTE,
        self::SECOND_STR => self::SECOND,
        self::TYPE_STR => self::TYPE,
        self::OBJECT_STR => self::OBJECT,
        self::OBJECTS_STR => self::OBJECTS,
    ];
    /**
     * Year default route
     *
     * @var int
     */
    const YEAR = 1;
    /**
     * Month default route
     *
     * @var int
     */
    const MONTH = 2;
    /**
     * Day default route
     *
     * @var int
     */
    const DAY = 4;
    /**
     * Hour default route
     *
     * @var int
     */
    const HOUR = 8;
    /**
     * Minute default route
     *
     * @var int
     */
    const MINUTE = 16;
    /**
     * Second default route
     *
     * @var int
     */
    const SECOND = 32;
    /**
     * Type default route
     *
     * @var int
     */
    const TYPE = 64;
    /**
     * Object default route
     *
     * @var int
     */
    const OBJECT = 128;
    /**
     * Objects default route
     *
     * @var int
     */
    const OBJECTS = 256;
    /**
     * All default routes
     *
     * @var int
     */
    const ALL = 511;
    /**
     * Month route
     *
     * @var string
     */
    const YEAR_STR = 'year';
    /**
     * Month route
     *
     * @var string
     */
    const MONTH_STR = 'month';
    /**
     * Day route
     *
     * @var string
     */
    const DAY_STR = 'day';
    /**
     * Hour route
     *
     * @var string
     */
    const HOUR_STR = 'hour';
    /**
     * Minute route
     *
     * @var string
     */
    const MINUTE_STR = 'minute';
    /**
     * Second route
     *
     * @var string
     */
    const SECOND_STR = 'second';
    /**
     * Object
     *
     * @var string
     */
    const OBJECT_STR = 'object';
    /**
     * Objects
     *
     * @var string
     */
    const OBJECTS_STR = 'objects';
    /**
     * Type
     *
     * @var string
     */
    const TYPE_STR = 'type';

    /**
     * Return a bit value for a particular default route name
     *
     * @param string $name Default route name
     * @return int Default route bit
     */
    public static function nameToBit($name)
    {
        return (empty($name) || empty(self::$nameBits[$name])) ? 0 : self::$nameBits[$name];
    }
}
