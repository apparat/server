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
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\ObjectsAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Infrastructure\Model\Server;
use Apparat\Server\Infrastructure\Route\AuraDefaultRoute;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

/**
 * Default routes test
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class DefaultRoutesTest extends AbstractServerTest
{
    /**
     * Server instance
     *
     * @var Server
     */
    protected static $server = null;
    /**
     * Configured object date precision
     *
     * @var int
     */
    protected static $objectDatePrecision;

    /**
     * This method is called before the first test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$objectDatePrecision = getenv('OBJECT_DATE_PRECISION');
        putenv('OBJECT_DATE_PRECISION=6');

        self::$server = Kernel::create(Server::class);
        self::$server->registerRepositoryDefaultRoutes();
    }

    /**
     * This method is called after the last test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        putenv('OBJECT_DATE_PRECISION='.self::$objectDatePrecision);
    }

    /**
     * Test dispatching default route requests to actions
     *
     * @dataProvider getDefaultRouteRequestAction
     * @param string $request Server request
     * @param string $actionClass Action class
     */
    public function testDefaultRouteAction($request, $actionClass)
    {
        $uri = new Uri($request);
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        // Dispatch the route
        $route = self::$server->dispatchRequestToRoute($request);
        $this->assertInstanceOf(AuraDefaultRoute::class, $route);

        // Get the associated action
        $action = self::$server->getRouteAction($request, $route);
        $this->assertInstanceOf($actionClass, $action);
    }

    /**
     * Provide default route requests and expected action classes
     */
    public function getDefaultRouteRequestAction()
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
            ['http://apparat/blog/2016/06/08/19/14/52/1-article', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/.1-article', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/1', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-*/*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article/.1-*', ObjectAction::class],
            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1.md', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1.*', ObjectAction::class],

            ['http://apparat/blog/2016/06/08/19/14/52/*', ObjectsAction::class],

//            ['http://apparat/blog/2016/06/08/19/14/52/*-article', TypeAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/*-article/*', TypeAction::class],
        ];

//
    }

    public function getFaultyDefaultRouteRequestAction() {
        return [
//            ['http://apparat/blog/2016/06/08/19/14/52/1', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/.1', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-*', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/.1-article', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-*', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1.md', ObjectAction::class],
//            ['http://apparat/blog/2016/06/08/19/14/52/1-article/1-1.*', ObjectAction::class],
//
//            ['http://apparat/blog/2016/06/08/19/14/52/*', ObjectsAction::class],
        ];
    }
}
