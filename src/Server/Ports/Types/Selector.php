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
 * Selector types
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class Selector
{
    /**
     * Month part
     *
     * @var string
     */
    const YEAR = 'year';
    /**
     * Month part
     *
     * @var string
     */
    const MONTH = 'month';
    /**
     * Day part
     *
     * @var string
     */
    const DAY = 'day';
    /**
     * Hour part
     *
     * @var string
     */
    const HOUR = 'hour';
    /**
     * Minute part
     *
     * @var string
     */
    const MINUTE = 'minute';
    /**
     * Second part
     *
     * @var string
     */
    const SECOND = 'second';
    /**
     * Hidden part
     *
     * @var string
     */
    const HIDDEN = 'hidden';
    /**
     * ID part
     *
     * @var string
     */
    const ID = 'id';
    /**
     * Type part
     *
     * @var string
     */
    const TYPE = 'type';
    /**
     * Revision part
     *
     * @var string
     */
    const REVISION = 'revision';
    /**
     * Draft part
     *
     * @var string
     */
    const DRAFT = 'draft';
    /**
     * Format part
     *
     * @var string
     */
    const FORMAT = 'format';
    /**
     * Wildcard
     *
     * @var string
     */
    const WILDCARD = '*';
}
