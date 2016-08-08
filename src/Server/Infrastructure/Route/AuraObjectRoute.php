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

use Apparat\Server\Domain\Contract\ObjectActionRouteInterface;
use Apparat\Server\Ports\Route\InvalidArgumentException;

/**
 * Aura default route
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 * @property-read array $attributes Attribute values added by the rules
 * @property-read array|Callable|\Closure|string $handler Action handler
 */
class AuraObjectRoute extends AuraRoute implements ObjectActionRouteInterface
{
    /**
     * Get the action handler
     *
     * @param mixed $parameters Handler parameters
     * @return Callable|\Closure|string Action handler
     * @throws InvalidArgumentException If the route action doesn't match
     */
    public function getHandler(&$parameters)
    {
        // Run through all registered handler classes
        foreach ($this->handler as $actionClass) {
            // If the request matches the handler class requirements
            if (call_user_func([$actionClass, 'matches'], $this->attributes)) {
                return $actionClass;
            }
        }

        throw new InvalidArgumentException(
            "Route action doesn't match",
            InvalidArgumentException::ROUTE_ACTION_DOESNT_MATCH
        );
    }
}
