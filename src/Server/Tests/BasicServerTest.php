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
use Apparat\Object\Infrastructure\Repository\FileAdapterStrategy;
use Apparat\Object\Ports\Facades\RepositoryFacade;
use Apparat\Server\Domain\Model\Server;
use Apparat\Server\Infrastructure\Model\Server as InfrastructureServer;
use Apparat\Server\Infrastructure\Route\AuraErrorRoute;
use Apparat\Server\Ports\Facade\ServerFacade;
use Apparat\Server\Ports\Route\Route;
use Apparat\Server\Ports\Types\ObjectRoute;
use Apparat\Server\Ports\View\TYPO3FluidView;
use Apparat\Server\Tests\Adr\TestAction;
use Apparat\Server\Tests\Adr\TestModule;
use Apparat\Server\Tests\Adr\TestObjectAction;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
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

        // Register a repositoryc
        RepositoryFacade::register(
            'repo',
            [
                'type' => FileAdapterStrategy::TYPE,
                'root' => __DIR__.DIRECTORY_SEPARATOR.'Fixture',
            ]
        );
    }

    /**
     * Test the server instantiation
     */
    public function testServerInstance()
    {
        $server = Kernel::create(Server::class);
        $this->assertInstanceOf(Server::class, $server);
    }

    /**
     * Test registering and dispatching a route
     */
    public function testRegisterDispatchRoute()
    {
        $route = new Route(Route::GET, 'default', '/default/{id}{format}', TestAction::class);
        $route->setTokens([
            'id' => '\d+',
            'format' => '(\.[^/]+)?',
        ]);
        ServerFacade::registerRoute($route);

        $uri = new Uri('http://apparat/blog/default/1.html');
        $request = new ServerRequest();
        $request = $request->withUri($uri);
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * Test registering and dispatching a route with callable handler
     */
    public function testRegisterDispatchRouteCallableHandler()
    {
        $route = new Route(
            Route::GET,
            'default2',
            '/handler/{id}{format}',
            function () {
                return Kernel::create(Response::class, []);
            }
        );
        $route->setTokens([
            'id' => '\d+',
            'format' => '(\.[^/]+)?',
        ]);
        ServerFacade::registerRoute($route);

        $uri = new Uri('http://apparat/blog/handler/1.html');
        $request = new ServerRequest();
        $request = $request->withUri($uri);
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * Test a route mismatch
     */
    public function testRouteMismatch()
    {
        $uri = new Uri('http://apparat/blog/invalid-route');
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        /** @var InfrastructureServer $server */
        $server = Kernel::create(InfrastructureServer::class);
        $route = new Route(Route::GET, 'default', '/default/{id}{format}', TestAction::class);
        $server->registerRoute($route);

        $this->assertInstanceOf(AuraErrorRoute::class, $server->dispatchRequestToRoute($request));
    }

    /**
     * Test a handler mismatch
     *
     * @expectedException \Apparat\Server\Ports\Route\InvalidArgumentException
     * @expectedExceptionCode 1468918389
     */
    public function testHandlerMismatch()
    {
        $uri = new Uri('http://apparat/blog/invalid-handler');
        $request = new ServerRequest();
        $request = $request->withUri($uri);

        /** @var InfrastructureServer $server */
        $server = Kernel::create(InfrastructureServer::class);
        $route = new Route(
            Route::GET,
            'default',
            '/invalid-handler',
            [ObjectRoute::OBJECT_STR => TestObjectAction::class]
        );
        $server->registerRoute($route);

        $route = $server->dispatchRequestToRoute($request);
        $server->getRouteAction($request, $route);
    }

    /**
     * Test custom template resources
     */
    public function testCustomTemplateResources()
    {
        // Enable the default routes
        ServerFacade::enableObjectRoute('repo');

        $noneRepoPath = __DIR__.DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'non-repo'.DIRECTORY_SEPARATOR;
        ServerFacade::setViewResources([
            TYPO3FluidView::LAYOUTS => $noneRepoPath.'Layouts'.DIRECTORY_SEPARATOR,
            TYPO3FluidView::TEMPLATES => $noneRepoPath.'Templates'.DIRECTORY_SEPARATOR,
            TYPO3FluidView::PARTIALS => $noneRepoPath.'Partials'.DIRECTORY_SEPARATOR,
        ]);

        // Enable the default routes for a repository "repo"
        $uri = new Uri('http://apparat/blog/repo/2016/06/20/2');
        $request = new ServerRequest();
        $request = $request->withUri($uri);
        $response = ServerFacade::dispatchRequest($request);
        $this->assertEquals('[(article)]', trim($response->getBody()));
    }
}
