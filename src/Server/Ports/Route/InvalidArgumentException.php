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

namespace Apparat\Server\Ports\Route;

/**
 * Invalid server argument exception
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * Empty route HTTP verbs
     *
     * @var int
     */
    const EMPTY_ROUTE_HTTP_VERBS = 1464520768;
    /**
     * Invalid route HTTP verb
     *
     * @var int
     */
    const INVALID_ROUTE_HTTP_VERB = 1464520896;
    /**
     * Empty route name
     *
     * @var int
     */
    const EMPTY_ROUTE_NAME = 1464521013;
    /**
     * Empty route path
     *
     * @var int
     */
    const EMPTY_ROUTE_PATH = 1464521073;
    /**
     * Empty route action
     *
     * @var int
     */
    const EMPTY_ROUTE_ACTION = 1464521136;
    /**
     * Route action must be a class name
     *
     * @var int
     */
    const ROUTE_ACTION_MUST_BE_CLASSNAME = 1464521937;
    /**
     * Empty route action
     *
     * @var int
     */
    const ROUTE_ACTION_NOT_CALLABLE_OR_ACTION_INTERFACE = 1464521506;
    /**
     * Route action must implement ActionInterface
     *
     * @var int
     */
    const ROUTE_ACTION_MUST_IMPLEMENT_ACTION_INTERFACE = 1464522202;
}
