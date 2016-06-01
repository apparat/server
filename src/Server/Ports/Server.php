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

namespace Apparat\Server\Ports;

use Apparat\Kernel\Ports\Kernel;
use Apparat\Object\Ports\Object;
use Apparat\Server\Domain\Contract\RouteInterface;
use Apparat\Server\Ports\Action\DayAction;
use Apparat\Server\Ports\Action\HourAction;
use Apparat\Server\Ports\Action\MinuteAction;
use Apparat\Server\Ports\Action\MonthAction;
use Apparat\Server\Ports\Action\ObjectAction;
use Apparat\Server\Ports\Action\SecondAction;
use Apparat\Server\Ports\Action\YearAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Server facade
 *
 * @package Apparat\Server\Ports
 */
class Server
{
    /**
     * Server instance
     *
     * @var \Apparat\Server\Domain\Model\Server
     */
    protected static $server = null;
    /**
     * Default repository routes
     *
     * @array
     */
    const DEFAULT_ROUTES = [
        Route::SECOND => [
            '/{year}/{month}/{day}/{hour}/{minute}/{second}',
            self::TOKEN_YEAR +
            self::TOKEN_MONTH +
            self::TOKEN_DAY +
            self::TOKEN_HOUR +
            self::TOKEN_MINUTE +
            self::TOKEN_SECOND,
            SecondAction::class
        ],
        Route::MINUTE => [
            '/{year}/{month}/{day}/{hour}/{minute}',
            self::TOKEN_YEAR +
            self::TOKEN_MONTH +
            self::TOKEN_DAY +
            self::TOKEN_HOUR +
            self::TOKEN_MINUTE,
            MinuteAction::class
        ],
        Route::HOUR => [
            '/{year}/{month}/{day}/{hour}',
            self::TOKEN_YEAR +
            self::TOKEN_MONTH +
            self::TOKEN_DAY +
            self::TOKEN_HOUR,
            HourAction::class
        ],
        Route::DAY => [
            '/{year}/{month}/{day}',
            self::TOKEN_YEAR +
            self::TOKEN_MONTH +
            self::TOKEN_DAY,
            DayAction::class
        ],
        Route::MONTH => [
            '/{year}/{month}',
            self::TOKEN_YEAR +
            self::TOKEN_MONTH,
            MonthAction::class
        ],
        Route::YEAR => [
            '/{year}',
            self::TOKEN_YEAR,
            YearAction::class
        ]
    ];
    /**
     * Year route token
     *
     * @var array
     */
    const TOKEN_YEAR = ['year' => self::REGEX_ASTERISK.'|(?:\d{4})'];
    /**
     * Month route token
     *
     * @var array
     */
    const TOKEN_MONTH = ['month' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:1[0-2])'];
    /**
     * Day route token
     *
     * @var array
     */
    const TOKEN_DAY = ['day' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:[1-2]\d)|(?:3[0-1])'];
    /**
     * Hour route token
     *
     * @var array
     */
    const TOKEN_HOUR = ['hour' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:1[0-2])'];
    /**
     * Day route token
     *
     * @var array
     */
    const TOKEN_MINUTE = ['minute' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:[1-4]\d)|(?:5[0-9])'];
    /**
     * Second route token
     *
     * @var array
     */
    const TOKEN_SECOND = ['second' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:[1-4]\d)|(?:5[0-9])'];
    /**
     * Asterisk regular expression
     *
     * @var string
     */
    const REGEX_ASTERISK = '(?:\%2A)';

    /**
     * Register a route
     *
     * @param RouteInterface $route
     */
    public static function registerRoute(RouteInterface $route)
    {
        self::getServer()->registerRoute($route);
    }

    public static function registerRepositoryDefaultRoutes(/*RepositoryInterface $repository, $enable = true*/)
    {
        // Build the list of base routes
        $dateDefaultRoutes = array_slice(self::DEFAULT_ROUTES, 6 - getenv('OBJECT_DATE_PRECISION'));
        $numDefaultRoutes = count($dateDefaultRoutes);
        $baseDateRoute = current($dateDefaultRoutes);
        $enabledObjectTypes = '(?:-(?:(?:'.implode(')|(?:', array_map('preg_quote', Object::getSupportedTypes())).')))';
        $objectRoute = [
            $baseDateRoute[0].'/{hidden}{id}{type}{draft}{revision}{format}',
            $baseDateRoute[1] + [
                'hidden' => '\.?',
                'id' => self::REGEX_ASTERISK.'|(?:\d+)',
                'type' => $enabledObjectTypes.'?',
                'draft' => '(?:/(\.)?\\'.(2 + $numDefaultRoutes).')?',
                'revision' => '(?('.(5 + $numDefaultRoutes).')|(?:-\d+)?)',
                'format' => '(?('.(4 + $numDefaultRoutes).')(?:\.'.preg_quote(getenv('OBJECT_RESOURCE_EXTENSION')).')?)',
            ],
            ObjectAction::class
        ];
        $defaultRoutes = [Route::OBJECT => $objectRoute] + $dateDefaultRoutes;

        // Iterate through and register all base routes
        foreach ($defaultRoutes as $routeName => $routeConfig) {
            $route = new Route(Route::GET, $routeName, $routeConfig[0], $routeConfig[2], true);
            self::registerRoute($route->setTokens($routeConfig[1]));
        }
    }

    /**
     * Dispatch a request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface $response
     */
    public static function dispatchRequest(ServerRequestInterface $request)
    {
        self::getServer()->dispatchRequest($request);
    }

    /**
     * Create and return the server instance
     *
     * @return \Apparat\Server\Domain\Model\Server Server instance
     */
    protected static function getServer()
    {
        if (self::$server === null) {
            self::$server = Kernel::create(\Apparat\Server\Domain\Model\Server::class);
        }
        return self::$server;
    }
}
