<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure\Model
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

namespace Apparat\Server\Infrastructure\Model;

use Apparat\Object\Ports\Types\Object;
use Apparat\Server\Domain\Contract\ActionRouteInterface;
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Ports\Action\ActionInterface;
use Apparat\Server\Ports\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Server instance
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class Server extends \Apparat\Server\Domain\Model\Server
{
    /**
     * Asterisk regular expression
     *
     * @var string
     */
    const REGEX_ASTERISK = '(?:\%2A)';
    /**
     * Year route token
     *
     * @var array
     */
    protected static $TOKEN_YEAR = ['year' => self::REGEX_ASTERISK.'|(?:\d{4})'];
    /**
     * Month route token
     *
     * @var array
     */
    protected static $TOKEN_MONTH = ['month' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:1[0-2])'];
    /**
     * Day route token
     *
     * @var array
     */
    protected static $TOKEN_DAY = ['day' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:[1-2]\d)|(?:3[0-1])'];
    /**
     * Hour route token
     *
     * @var array
     */
    protected static $TOKEN_HOUR = ['hour' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:1[0-2])'];
    /**
     * Day route token
     *
     * @var array
     */
    protected static $TOKEN_MINUTE = ['minute' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:[1-4]\d)|(?:5[0-9])'];
    /**
     * Second route token
     *
     * @var array
     */
    protected static $TOKEN_SECOND = ['second' => self::REGEX_ASTERISK.'|(?:0[1-9])|(?:[1-4]\d)|(?:5[0-9])'];

    /**
     * Build and return the default date routes
     *
     * @param string $prefix Repository route prefix
     * @param int $precision Date precision
     * @return array Default date routes
     */
    protected function buildDefaultDateRoutes($prefix, $precision)
    {
        return array_slice(
            [
                Route::SECOND => [
                    $prefix.'/{year}/{month}/{day}/{hour}/{minute}/{second}',
                    self::$TOKEN_YEAR +
                    self::$TOKEN_MONTH +
                    self::$TOKEN_DAY +
                    self::$TOKEN_HOUR +
                    self::$TOKEN_MINUTE +
                    self::$TOKEN_SECOND,
                    SecondAction::class
                ],
                Route::MINUTE => [
                    $prefix.'/{year}/{month}/{day}/{hour}/{minute}',
                    self::$TOKEN_YEAR +
                    self::$TOKEN_MONTH +
                    self::$TOKEN_DAY +
                    self::$TOKEN_HOUR +
                    self::$TOKEN_MINUTE,
                    MinuteAction::class
                ],
                Route::HOUR => [
                    $prefix.'/{year}/{month}/{day}/{hour}',
                    self::$TOKEN_YEAR +
                    self::$TOKEN_MONTH +
                    self::$TOKEN_DAY +
                    self::$TOKEN_HOUR,
                    HourAction::class
                ],
                Route::DAY => [
                    $prefix.'/{year}/{month}/{day}',
                    self::$TOKEN_YEAR +
                    self::$TOKEN_MONTH +
                    self::$TOKEN_DAY,
                    DayAction::class
                ],
                Route::MONTH => [
                    $prefix.'/{year}/{month}',
                    self::$TOKEN_YEAR +
                    self::$TOKEN_MONTH,
                    MonthAction::class
                ],
                Route::YEAR => [
                    $prefix.'/{year}',
                    self::$TOKEN_YEAR,
                    YearAction::class
                ]
            ],
            6 - $precision
        );
    }

    /**
     * Build and return the default object routes
     *
     * @param string $prefix Repository route prefix
     * @param array $baseDateRoute Base date route
     * @param int $numDefaultRoutes Total number of date routes
     * @return array Default object routes
     */
    protected function buildDefaultObjectRoutes($prefix, $baseDateRoute, $numDefaultRoutes)
    {
        // Build a regular expression for all supported object types
        $enabledObjectTypes = '(?:-(?:(?:'.implode(')|(?:', array_map('preg_quote', Object::getSupportedTypes())).')))';
        $objectResourceExt = getenv('OBJECT_RESOURCE_EXTENSION');

        return [
            // Default object route
            Route::OBJECT => [
                $prefix.$baseDateRoute[0].'/{hidden}{id}{type}{draft}{revision}{format}',
                $baseDateRoute[1] + [
                    'hidden' => '\.?',
                    'id' => '\d+',
                    'type' => $enabledObjectTypes.'?',
                    'draft' => '(?:/(\.)?\\'.(2 + $numDefaultRoutes).')?',
                    'revision' => '(?('.(5 + $numDefaultRoutes).')|(?:-\d+)?)',
                    'format' => '(?('.(4 + $numDefaultRoutes).')(?:\.'.preg_quote($objectResourceExt).')?)',
                ],
                ObjectAction::class
            ],
            // Default type route
            Route::TYPE => [
                $baseDateRoute[0].'/{hidden}{id}{type}{draft}{revision}{format}',
                $baseDateRoute[1] + [
                    'hidden' => '\.?',
                    'id' => self::REGEX_ASTERISK,
                    'type' => $enabledObjectTypes.'?',
                    'draft' => '(?:/(\.)?'.self::REGEX_ASTERISK.')?',
                    'revision' => '(?('.(5 + $numDefaultRoutes).')|(?:-\d+)?)',
                    'format' => '(?('.(4 + $numDefaultRoutes).')(?:\.'.preg_quote($objectResourceExt).')?)',
                ],
                TypeAction::class
            ]
        ];
    }

    /**
     * Register the default routes for a particular repository
     *
     * @param string $repositoryPath Repository path
     * @param bool $enable Enable / disable default routes
     */
    public function registerRepositoryDefaultRoutes($repositoryPath = '', $enable = true)
    {
        // Repository route prefix
        $prefix = rtrim('/'.$repositoryPath, '/');

        // Build the list of default routes
        $defaultDateRoutes = $this->buildDefaultDateRoutes($prefix, getenv('OBJECT_DATE_PRECISION'));
        $baseDateRoute = count($defaultDateRoutes) ? current($defaultDateRoutes) : ['/', []];
        $defaultObjectRoutes = $this->buildDefaultObjectRoutes($prefix, $baseDateRoute, count($defaultDateRoutes));
        $defaultRoutes = $defaultObjectRoutes + $defaultDateRoutes;

        // Iterate through and register all base routes
        foreach ($defaultRoutes as $routeName => $routeConfig) {
            $route = new Route(Route::GET, $routeName, $routeConfig[0], $routeConfig[2], true);
            $this->registerRoute($route->setTokens($routeConfig[1]));
        }
    }

    /**
     * Prepare and return a route action
     *
     * @param ServerRequestInterface $request Request
     * @param ActionRouteInterface $route Route
     * @return ActionInterface|Callable $action Action
     */
    public function getRouteAction(ServerRequestInterface $request, ActionRouteInterface $route = null)
    {
        return $this->routerContainer->getRouteAction($request, $route);
    }
}
