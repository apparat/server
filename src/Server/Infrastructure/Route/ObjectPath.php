<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\<Layer>
 * @author      Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright   Copyright © 2016 Joschi Kuphal <joschi@kuphal.net> / @jkphl
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

use Apparat\Server\Domain\Contract\ObjectActionRouteInterface;
use Aura\Router\Route;
use Aura\Router\Rule\Path;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Object path rule
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class ObjectPath extends Path
{
    /**
     *
     * Check if the Request matches the Route.
     *
     * @param ServerRequestInterface $request The HTTP request.
     * @param Route $route The route.
     * @return bool True on success, false on failure.
     */
    public function __invoke(ServerRequestInterface $request, Route $route)
    {
        if ($route instanceof ObjectActionRouteInterface) {
            return $this->matchObjectSelector($request, $route);
        }

        return parent::__invoke($request, $route);
    }

    /**
     * Check if the request matches the object route
     *
     * @param ServerRequestInterface $request Request
     * @param Route $route Object route
     * @return bool True on success, false on failure
     */
    protected function matchObjectSelector(ServerRequestInterface $request, Route $route) {
        // Try to match the object selector
        $match = preg_match(
            '%^'.$this->basepath.$route->path.'$%',
            rawurldecode($request->getUri()->getPath()),
            $matches
        );

        if (!$match) {
            return false;
        }

        $route->attributes($this->getAttributes($matches, $route->wildcard));
        return true;
    }
}
