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

use Apparat\Object\Infrastructure\Repository\FileAdapterStrategy;
use Apparat\Object\Ports\Facades\RepositoryFacade;
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\ObjectsAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Infrastructure\Route\AuraObjectRoute;
use Apparat\Server\Ports\Facade\ServerFacade;
use Apparat\Server\Ports\Types\ObjectRoute;
use Apparat\Server\Tests\Adr\TestMultipleObjectAction;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

/**
 * Default route test
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class ObjectRouteTest extends AbstractServerTest
{
    /**
     * Test dispatching default route requests to actions
     *
     * @dataProvider getObjectRouteRequestAction
     * @param string $request Server request
     * @param string $actionClass Action class
     */
    public function testObjectRouteAction($request, $actionClass)
    {
        $uri = new Uri($request);
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        // Dispatch the route
        $route = self::$server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraObjectRoute::class, $route);

        // Get the associated action
        $action = self::$server->getRouteAction($request, $route);
        $this->assertInstanceOf($actionClass, $action);
    }

    /**
     * Provide default route requests and expected action classes
     */
    public function getObjectRouteRequestAction()
    {
        return [
            ['http://apparat/blog/2016', YearAction::class],
            ['http://apparat/blog/*', YearAction::class],

            ['http://apparat/blog/2016/06', MonthAction::class],
            ['http://apparat/blog/*/*', MonthAction::class],

            ['http://apparat/blog/2016/06/08', DayAction::class],
            ['http://apparat/blog/*/*/*', DayAction::class],

            ['http://apparat/blog/2016/06/08/19', HourAction::class],
            ['http://apparat/blog/*/*/*/*', HourAction::class],

            ['http://apparat/blog/2016/06/08/19/14', MinuteAction::class],
            ['http://apparat/blog/*/*/*/*/*', MinuteAction::class],

            ['http://apparat/blog/2016/06/08/19/14/52', SecondAction::class],
            ['http://apparat/blog/*/*/*/*/*/*', SecondAction::class],

            ['http://apparat/blog/2016/06/08/19/14/52/1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/.1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/~1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/.1-article', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/~1-article', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/.1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/.*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/~1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/~*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.1-1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/~1-1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/*-1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.*-1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/~*-1', ObjectAction::class],

            ['http://apparat/blog/2016/06/08/19/14/52/*-article', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/.*-article', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/~*-article', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/*', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/.*', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/~*', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/*-1', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/*-*', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/.*-1', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/.*-*', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/~*-1', TypeAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-article/~*-*', TypeAction::class],

            ['http://apparat/blog/2016/06/08/19/14/52/*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/.*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/~*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*/*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*/.*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/.*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/~*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/*-1', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/*-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/.*-1', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/.*-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/~*-1', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/*-*/~*-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.1-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/~1-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/*-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.*-*', ObjectsAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/~*-*', ObjectsAction::class],
        ];
    }

    /**
     * Test the object route name to bit translation
     */
    public function testObjectRouteNamesToBits()
    {
        $this->assertEquals(ObjectRoute::YEAR, ObjectRoute::nameToBit(ObjectRoute::YEAR_STR));
        $this->assertEquals(ObjectRoute::MONTH, ObjectRoute::nameToBit(ObjectRoute::MONTH_STR));
        $this->assertEquals(ObjectRoute::DAY, ObjectRoute::nameToBit(ObjectRoute::DAY_STR));
        $this->assertEquals(ObjectRoute::HOUR, ObjectRoute::nameToBit(ObjectRoute::HOUR_STR));
        $this->assertEquals(ObjectRoute::MINUTE, ObjectRoute::nameToBit(ObjectRoute::MINUTE_STR));
        $this->assertEquals(ObjectRoute::SECOND, ObjectRoute::nameToBit(ObjectRoute::SECOND_STR));
        $this->assertEquals(ObjectRoute::TYPE, ObjectRoute::nameToBit(ObjectRoute::TYPE_STR));
        $this->assertEquals(ObjectRoute::OBJECT, ObjectRoute::nameToBit(ObjectRoute::OBJECT_STR));
        $this->assertEquals(ObjectRoute::OBJECTS, ObjectRoute::nameToBit(ObjectRoute::OBJECTS_STR));
    }

    /**
     * Test an empty object result
     */
    public function testObjectNotFound()
    {
        $uri = new Uri('http://apparat/blog/2016/06/08/19/14/52/1');
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        // Dispatch the route
        $route = self::$server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraObjectRoute::class, $route);

        // Get the associated action
        $action = self::$server->getRouteAction($request, $route);
        $this->assertInstanceOf(ObjectAction::class, $action);

        // Run the action
        /** @var ResponseInterface $response */
        $response = $action();
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }


    /**
     * Test multiple object error
     */
//    public function testMultipleObjectError()
//    {
//        $uri = new Uri('http://apparat/blog/*');
//        $request = new ServerRequest();
//        $request = $request->withUri($uri);
//
//        // Dispatch the route
//        $route = self::$server->dispatchRequestToRoute($request);
//        $this->assertInstanceOf(AuraObjectRoute::class, $route);
//        $route->handler([ObjectRoute::YEAR_STR => TestMultipleObjectAction::class]);
//
//        // Get the associated action
//        $action = self::$server->getRouteAction($request, $route);
//        $this->assertInstanceOf(TestMultipleObjectAction::class, $action);
//
//        // Run the action
//        $response = $action();
//        $this->assertInstanceOf(ResponseInterface::class, $response);
//        $this->assertEquals(500, $response->getStatusCode());
//    }
}
