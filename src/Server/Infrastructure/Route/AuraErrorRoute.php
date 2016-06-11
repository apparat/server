<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure\Route
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
use Apparat\Server\Domain\Contract\ErrorRouteInterface;
use Apparat\Server\Infrastructure\Action\ErrorAction;
use Aura\Router\Route;

/**
 * Error action route
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class AuraErrorRoute extends AbstractActionRoute implements ErrorRouteInterface
{
    /**
     * Pre-process the route attributes
     */
    public function preprocessAttributes()
    {
        parent::preprocessAttributes();

        $this->attributes['failure'] = $this->failedRule;
    }

    /**
     * Cast an regular route as an error route
     *
     * @param Route $route Regular route
     * @return AuraErrorRoute Error route
     */
    public static function cast(Route $route)
    {
        /** @var AuraErrorRoute $errorRoute */
        $errorRoute = Kernel::create(static::class, []);
        foreach (get_object_vars($route) as $property => $value) {
            $errorRoute->$property = $value;
        }
        return $errorRoute->handler(ErrorAction::class);
    }
}
