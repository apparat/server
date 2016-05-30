<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure
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

namespace Apparat\Server\Infrastructure;

use Apparat\Kernel\Ports\Kernel;
use Apparat\Server\Domain\Contract\RouteInterface;
use Apparat\Server\Domain\Contract\RouterContainerInterface;
use Apparat\Server\Ports\Action\ActionInterface;
use Aura\Router\Matcher;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Aura.Router adapter
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class AuraRouterAdapter implements RouterContainerInterface
{
    /**
     * Aura.Router container
     *
     * @var RouterContainer
     */
    protected $routerContainer;

    /**
     * Constructor
     *
     * @param RouterContainer $routerContainer Router container
     */
    public function __construct(RouterContainer $routerContainer)
    {
        $this->routerContainer = $routerContainer;
    }

    /**
     * Register a route
     *
     * @param RouteInterface $route Route
     * @return RouterContainerInterface Self reference
     */
    public function registerRoute(RouteInterface $route)
    {
        $this->routerContainer->getMap()
            ->route($route->getName(), $route->getPath(), $route->getAction())
            ->allows($route->getVerbs())
            ->tokens($route->getTokens())
            ->defaults($route->getDefaults())
            ->wildcard($route->getWildcard())
            ->host($route->getHost())
            ->accepts($route->getAccepts())
            ->auth($route->getAuth())
            ->secure($route->getSecure())
            ->extras($route->getExtras());
        return $this;
    }

    /**
     * Dispatch a request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface $response
     */
    public function dispatchRequest(ServerRequestInterface $request)
    {
        $matcher = $this->routerContainer->getMatcher();
        $route = $matcher->match($request);

        // If a registered Route could be matched
        if ($route instanceof Route) {
            return $this->handleRequestRoute($request, $route);
        }

        // Handle the request mismatch
        return $this->handleRequestMismatch($request, $matcher);
    }

    /**
     * Handle a matched route request
     *
     * @param ServerRequestInterface $request Server request
     * @param Route $route Matched route
     * @return ResponseInterface $response
     */
    protected function handleRequestRoute(ServerRequestInterface $request, Route $route) {
        // Copy all route attributes to the server request
        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        $handler = $route->handler;

        // If the handler is a callable
        if (is_callable($handler)) {
            return $handler($request);
        }

        /** @var ActionInterface $handler */
        $handler = Kernel::create($handler, [$request]);
        return $handler();
    }

    /**
     * Handle a mismatched request
     *
     * @param ServerRequestInterface $request Server request
     * @param Matcher $matcher Matcher
     * @return ResponseInterface Response
     */
    protected function handleRequestMismatch(ServerRequestInterface $request, Matcher $matcher) {
        // TODO Error responder
//        // Instantiate a response
//        $response = Kernel::create(ResponseInterface::class);
//
//        // Get the first of the best-available non-matched routes
//        $failedRoute = $matcher->getFailedRoute();
//
//        // Which matching rule failed?
//        switch ($failedRoute->failedRule) {
//            case 'Aura\Router\Rule\Allows':
//                // 405 METHOD NOT ALLOWED
//                // Send the $failedRoute->allows as 'Allow:'
//                break;
//            case 'Aura\Router\Rule\Accepts':
//                // 406 NOT ACCEPTABLE
//                break;
//            default:
//                // 404 NOT FOUND
//                break;
//        }
//
//        return $response;
    }
}
