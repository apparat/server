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

use Apparat\Dev\Tests\AbstractTest;
use Apparat\Server\Ports\Authenticator\Bearer;
use Apparat\Server\Ports\Facade\ServerFacade;
use Apparat\Server\Ports\Route\RouteFactory;
use Apparat\Server\Ports\View\TYPO3FluidView;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

/**
 * Authenticator test
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class AuthenticatorTest extends AbstractTest
{
    /**
     * Test an invalid authenticator
     *
     * @expectedException \Apparat\Server\Ports\Authenticator\InvalidArgumentException
     * @expectedExceptionCode 1471206157
     */
    public static function testInvalidAuthenticator()
    {
        //  Register a static route and add the bearer token authenticator
        $bearerRoute = RouteFactory::createStaticRoute('/bearer', 'Test/Bearer');
        $bearerRoute->setAuth([new \stdClass()]);
        ServerFacade::registerRoute($bearerRoute);

        // Test authorization header
        $uri = new Uri('http://apparat/blog/bearer');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withAddedHeader('Authorization', 'Bearer');
        ServerFacade::dispatchRequest($request);
    }

    /**
     * Tears down the fixture
     */
    public function tearDown()
    {
        parent::tearDown();

        ServerFacade::reset();
    }

    /**
     * Test the bearer token
     */
    public function testBearerToken()
    {
        // Register custom view resources
        $noneRepoPath = __DIR__.DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'non-repo'.DIRECTORY_SEPARATOR;
        ServerFacade::setViewResources([
            TYPO3FluidView::LAYOUTS => $noneRepoPath.'Layouts'.DIRECTORY_SEPARATOR,
            TYPO3FluidView::TEMPLATES => $noneRepoPath.'Templates'.DIRECTORY_SEPARATOR,
            TYPO3FluidView::PARTIALS => $noneRepoPath.'Partials'.DIRECTORY_SEPARATOR,
        ]);

        $bearerToken = md5(microtime(true));
        $bearerAuthenticator = new Bearer(function ($currentToken) use ($bearerToken) {
            return $currentToken === $bearerToken;
        });

        //  Register a static route and add the bearer token authenticator
        $bearerRoute = RouteFactory::createStaticRoute('/bearer', 'Test/Bearer');
        $bearerRoute->setAuth([$bearerAuthenticator]);
        ServerFacade::registerRoute($bearerRoute);

        // Test authorization header
        $uri = new Uri('http://apparat/blog/bearer');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withAddedHeader('Authorization', 'Bearer '.$bearerToken);
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('[(bearer)]', trim($response->getBody()));

        // Test "access_token" body parameter
        $uri = new Uri('http://apparat/blog/bearer');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withParsedBody(['access_token' => $bearerToken]);
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('[(bearer)]', trim($response->getBody()));

        // Test "access_token" query parameter
        $uri = new Uri('http://apparat/blog/bearer');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withQueryParams(['access_token' => $bearerToken]);
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('[(bearer)]', trim($response->getBody()));

        // Test invalid authorization header
        $uri = new Uri('http://apparat/blog/bearer');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withAddedHeader('Authorization', 'Bearer');
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        // Test with basic authorization header
        $uri = new Uri('http://apparat/blog/bearer');
        $request = new ServerRequest();
        $request = $request->withUri($uri)->withAddedHeader('Authorization', 'Basic '.base64_encode("user:pass"));
        $response = ServerFacade::dispatchRequest($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
