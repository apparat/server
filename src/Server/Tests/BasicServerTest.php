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
use Apparat\Server\Ports\Route;
use Apparat\Server\Ports\Server;
use Apparat\Server\Tests\Adr\TestAction;
use Apparat\Server\Tests\Adr\TestModule;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

/**
 * Basic server test
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class BasicServerTest extends AbstractServerTest
{
    /**
     * This method is called before the first test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function setUpBeforeClass()
    {
        TestModule::autorun();
    }

    /**
     * Test the server instantiation
     */
    public function testServerInstance()
    {
        $server = Kernel::create(\Apparat\Server\Domain\Model\Server::class);
        $this->assertInstanceOf(\Apparat\Server\Domain\Model\Server::class, $server);
    }

    /**
     * Test registering and dispatching a route
     */
    public function testRegisterDispatchRoute()
    {
        $route = new Route('GET', 'default', '/default/{id}{format}', TestAction::class);
        $route->setTokens([
            'id' => '\d+',
            'format' => '(\.[^/]+)?',
        ]);
        Server::registerRoute($route);

        $uri = new Uri('http://apparat/blog/default/1.html');
        $request = new ServerRequest();
        $request = $request->withUri($uri);
        Server::dispatchRequest($request);
    }
}