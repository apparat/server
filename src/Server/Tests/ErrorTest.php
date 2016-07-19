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

use Apparat\Kernel\Ports\Kernel;
use Apparat\Server\Infrastructure\Action\ErrorAction;
use Apparat\Server\Infrastructure\Model\Server;
use Apparat\Server\Infrastructure\Route\AuraErrorRoute;
use Apparat\Server\Ports\Route\Route;
use Apparat\Server\Ports\Types\ObjectRoute;
use Apparat\Server\Tests\Adr\TestAction;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

/**
 * Error test
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class ErrorTest extends AbstractServerTest
{
    /**
     * Test an object route mismatch / bad object request
     */
    public function testObjectRouteMismatch()
    {
        $uri = new Uri('http://apparat/blog/');
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        // Dispatch the route
        $route = self::$server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraErrorRoute::class, $route);

        $action = self::$server->getRouteAction($request, $route);
        $this->assertInstanceOf(ErrorAction::class, $action);

        /** @var ResponseInterface $response */
        $response = $action();
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Test a disallowed HTTP method
     */
    public function testDisallowedMethod()
    {
        $uri = new Uri('http://apparat/blog/*');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withMethod(Route::POST);

        // Dispatch the route
        $route = self::$server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraErrorRoute::class, $route);

        $action = self::$server->getRouteAction($request, $route);
        $this->assertInstanceOf(ErrorAction::class, $action);

        /** @var ResponseInterface $response */
        $response = $action();
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(405, $response->getStatusCode());

        $allowHeader = $response->getHeader('allow');
        $this->assertArrayEquals([Route::GET], $allowHeader);
    }

    /**
     * Test an inacceptable response
     */
    public function testInacceptableResponse()
    {
        $uri = new Uri('http://apparat/blog/*');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withAddedHeader('Accept', 'application/object');

        // Dispatch the route
        $route = self::$server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraErrorRoute::class, $route);

        $action = self::$server->getRouteAction($request, $route);
        $this->assertInstanceOf(ErrorAction::class, $action);

        /** @var ResponseInterface $response */
        $response = $action();
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(406, $response->getStatusCode());

        $acceptHeader = $response->getHeader('accept');
        $this->assertArrayEquals(ObjectRoute::$accept, $acceptHeader);
    }

    /**
     * Test a route mismatch
     */
    public function testRouteMismatch()
    {
        /** @var Server $server */
        $server = Kernel::create(Server::class);
        $server->registerRoute(new Route(Route::GET, 'default', '/default/{id}{format}', TestAction::class));

        $uri = new Uri('http://apparat/blog/invalid-request');
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        // Dispatch the route
        $route = $server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraErrorRoute::class, $route);

        $action = $server->getRouteAction($request, $route);
        $this->assertInstanceOf(ErrorAction::class, $action);

        /** @var ResponseInterface $response */
        $response = $action();
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
