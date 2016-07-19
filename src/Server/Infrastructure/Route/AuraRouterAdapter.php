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

namespace Apparat\Server\Infrastructure\Route;

use Apparat\Kernel\Ports\Kernel;
use Apparat\Server\Domain\Contract\ActionRouteInterface;
use Apparat\Server\Domain\Contract\RouteInterface;
use Apparat\Server\Domain\Contract\RouterContainerInterface;
use Apparat\Server\Ports\Action\ActionInterface;
use Aura\Router\RouterContainer;
use Aura\Router\Rule;
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

        // Override the default rules
        /** @var Rule\RuleIterator $ruleIterator */
        $ruleIterator = $routerContainer->getRuleIterator();
        $ruleIterator->set([
            new Rule\Secure(),
            new Rule\Host(),
            new Rule\Allows(),
            new Rule\Accepts(),
        ]);
    }

    /**
     * Register a route
     *
     * @param RouteInterface $route Route
     * @return RouterContainerInterface Self reference
     */
    public function registerRoute(RouteInterface $route)
    {
        /** @var AuraRoute $auraRoute */
        $auraRoute = $route->isObject() ? $this->createAuraObjectRoute() : $this->createAuraRoute();
        $auraRoute->name($route->getName())
            ->path($route->getPath())
            ->allows($route->getVerbs())
            ->handler($route->getAction())
            ->tokens($route->getTokens())
            ->defaults($route->getDefaults())
            ->wildcard($route->getWildcard())
            ->host($route->getHost())
            ->accepts($route->getAccepts())
            ->auth($route->getAuth())
            ->secure($route->getSecure())
            ->extras($route->getExtras());
        $this->routerContainer->getMap()->addRoute($auraRoute);

        return $this;
    }

    /**
     * Instantiate and register an aura object route
     *
     * @return AuraObjectRoute Aura object route
     */
    protected function createAuraObjectRoute()
    {
        $ruleIterator = $this->routerContainer->getRuleIterator();
        $ruleIterator->append(new ObjectPath(rtrim(parse_url(getenv('APPARAT_BASE_URL'), PHP_URL_PATH), '/') ?: null));
        return Kernel::create(AuraObjectRoute::class);
    }

    /**
     * Instantiate and register an aura object route
     *
     * @return AuraRoute Aura route
     */
    protected function createAuraRoute()
    {
        $ruleIterator = $this->routerContainer->getRuleIterator();
        $ruleIterator->append(new Rule\Path(rtrim(parse_url(getenv('APPARAT_BASE_URL'), PHP_URL_PATH), '/') ?: null));
        return Kernel::create(AuraRoute::class);
    }

    /**
     * Dispatch a request to a route
     *
     * @param ServerRequestInterface $request
     * @return AbstractActionRoute $route
     */
    public function dispatchRequestToRoute(ServerRequestInterface $request)
    {
        $matcher = $this->routerContainer->getMatcher();
        $route = $matcher->match($request);

        // If a matching route was found
        if ($route instanceof ActionRouteInterface) {
            return $route;
        }

        // Else create an error route
        return AuraErrorRoute::cast($matcher->getFailedRoute());
    }

    /**
     * Prepare and return a route action
     *
     * @param ServerRequestInterface $request Request
     * @param AbstractActionRoute $route Route
     * @return ActionInterface|Callable $action Action
     */
    public function getRouteAction(ServerRequestInterface $request, AbstractActionRoute $route)
    {
        // Pre-process the matched attributes
        $route->preprocessAttributes();

        // Copy all route attributes to the server request
        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        /** @var AbstractActionRoute $route */
        $handler = $route->getHandler();

        // If the handler is a callable
        if (is_callable($handler)) {
            return function () use ($handler, $request) {
                /** @var ResponseInterface $response */
                $response = $handler($request);
                return $response;
            };
        }

        return Kernel::create($handler, [$request]);
    }
}
