<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Tests
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

namespace Apparat\Server\Tests;

use Apparat\Server\Ports\Route\Route;

/**
 * Route test
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class RouteTest extends AbstractServerTest
{
    /**
     * Test empty route verbs
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464520768
     */
    public function testEmptyRouteVerbs()
    {
        new Route('', 'name', 'path', 'action');
    }

    /**
     * Test invalid route verb
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464520896
     */
    public function testInvalidRouteVerb()
    {
        new Route('INVALID', 'name', 'path', 'action');
    }

    /**
     * Test empty route name
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464521013
     */
    public function testEmptyRouteName()
    {
        new Route(Route::GET, '', 'path', 'action');
    }

    /**
     * Test empty route path
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464521073
     */
    public function testEmptyRoutePath()
    {
        new Route(Route::GET, 'test', '', 'action');
    }

    /**
     * Test empty route action
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464521136
     */
    public function testEmptyRouteAction()
    {
        new Route(Route::GET, 'test', 'path', '');
    }

    /**
     * Test invalid route action class
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464521937
     */
    public function testInvalidRouteActionClass()
    {
        new Route(Route::GET, 'test', 'path', 'InvalidRouteActionClass');
    }

    /**
     * Test invalid route action interface
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464522202
     */
    public function testInvalidRouteActionInterface()
    {
        new Route(Route::GET, 'test', 'path', \stdClass::class);
    }

    /**
     * Test invalid route action
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1464521506
     */
    public function testInvalidRouteAction()
    {
        new Route(Route::GET, 'test', 'path', [true]);
    }

    /**
     * Test the route setters
     */
    public function testRouteSetters()
    {
        $route = new Route(
            Route::GET,
            'test',
            'path',
            function () {
            }
        );
        $route->setDefaults(['key' => 'value']);
        $route->setWildcard('wildcard');
        $route->setHost('apparat.tools');
        $route->setAccepts(['application/json']);
        $route->setVerbs([Route::POST]);
        $route->setSecure(true);
        $route->setAuth('credentials');
        $route->setExtras(['key' => 'value']);
        $this->assertInstanceOf(Route::class, $route);
    }
}
