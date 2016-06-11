<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Ports
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

namespace Apparat\Server\Ports\Facade;

use Apparat\Kernel\Ports\Kernel;
use Apparat\Object\Ports\Object;
use Apparat\Server\Infrastructure\Model\Server;
use Apparat\Server\Ports\Contract\RouteInterface;
use Apparat\Server\Ports\Types\DefaultRoute;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Server facade
 *
 * @package Apparat\Server\Ports
 */
class ServerFacade
{
    /**
     * Server instance
     *
     * @var \Apparat\Server\Domain\Model\Server
     */
    protected static $server = null;

    /**
     * Register the default routes for a particular repository
     *
     * @param string $repositoryPath Repository path
     * @param int $enable Enable / disable default routes
     * @api
     */
    public static function registerRepositoryDefaultRoutes($repositoryPath = '', $enable = DefaultRoute::ALL)
    {
        self::getServer()->registerRepositoryDefaultRoutes($repositoryPath, $enable);
    }

    /**
     * Register a route
     *
     * @param RouteInterface $route
     * @api
     */
    public static function registerRoute(RouteInterface $route)
    {
        self::getServer()->registerRoute($route);
    }

    /**
     * Dispatch a request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface $response
     */
    public static function dispatchRequest(ServerRequestInterface $request)
    {
        // Dispatch the request to a route
        $route = self::getServer()->dispatchRequestToRoute($request);

        // Get the appropriate route action
        $action = self::getServer()->getRouteAction($request, $route);

        // Run the action response
        return $action();
    }

    /**
     * Create and return the server instance
     *
     * @return Server Server instance
     */
    protected static function getServer()
    {
        if (self::$server === null) {
            self::$server = Kernel::create(Server::class);
        }
        return self::$server;
    }
}
