<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Domain\Model
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

namespace Apparat\Server\Domain\Model;

use Apparat\Server\Domain\Contract\ActionRouteInterface;
use Apparat\Server\Domain\Contract\RouteInterface;
use Apparat\Server\Domain\Contract\RouterContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Server
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Domain
 */
class Server
{
    /**
     * Router container
     *
     * @var RouterContainerInterface
     */
    protected $routerContainer;

    /**
     * Server constructor.
     *
     * @param RouterContainerInterface $routerContainer Router container
     */
    public function __construct(RouterContainerInterface $routerContainer)
    {
        $this->routerContainer = $routerContainer;
    }

    /**
     * Register a route
     *
     * @param RouteInterface $route
     */
    public function registerRoute(RouteInterface $route)
    {
        $this->routerContainer->registerRoute($route);
    }

    /**
     * Dispatch a request to a route
     *
     * @param ServerRequestInterface $request
     * @return ActionRouteInterface $route
     */
    public function dispatchRequestToRoute(ServerRequestInterface $request)
    {
        return $this->routerContainer->dispatchRequestToRoute($request);
    }
}
