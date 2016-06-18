<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure\Model
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

namespace Apparat\Server\Infrastructure\Model;

use Apparat\Object\Ports\Factory\SelectorFactory;
use Apparat\Server\Domain\Contract\ActionRouteInterface;
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\ObjectsAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Ports\Action\ActionInterface;
use Apparat\Server\Ports\Route\Route;
use Apparat\Server\Ports\Types\ObjectRoute;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Server instance
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class Server extends \Apparat\Server\Domain\Model\Server
{
    /**
     * Enable the object route for a particular repository
     *
     * @param string $repositoryPath Repository path
     * @param int $enable Enable / disable default routes
     */
    public function enableObjectRoute($repositoryPath = '', $enable = ObjectRoute::ALL)
    {
        // Repository route prefix
        $prefix = rtrim('/'.$repositoryPath, '/');
        $datePrecision = intval(getenv('OBJECT_DATE_PRECISION'));
        $selectorRegex = SelectorFactory::getSelectorRegex($datePrecision);
        $objectRouteActions = array_filter(
            array_merge(
                [
                    ObjectRoute::OBJECT_STR => (ObjectRoute::OBJECT & $enable) ? ObjectAction::class : null,
                    ObjectRoute::TYPE_STR => (ObjectRoute::TYPE & $enable) ? TypeAction::class : null,
                    ObjectRoute::OBJECTS_STR => (ObjectRoute::OBJECTS & $enable) ? ObjectsAction::class : null,
                ],
                array_slice(
                    [
                        ObjectRoute::SECOND_STR => (ObjectRoute::SECOND & $enable) ? SecondAction::class : null,
                        ObjectRoute::MINUTE_STR => (ObjectRoute::MINUTE & $enable) ? MinuteAction::class : null,
                        ObjectRoute::HOUR_STR => (ObjectRoute::HOUR & $enable) ? HourAction::class : null,
                        ObjectRoute::DAY_STR => (ObjectRoute::DAY & $enable) ? DayAction::class : null,
                        ObjectRoute::MONTH_STR => (ObjectRoute::MONTH & $enable) ? MonthAction::class : null,
                        ObjectRoute::YEAR_STR => (ObjectRoute::YEAR & $enable) ? YearAction::class : null,
                    ],
                    6 - $datePrecision
                )
            )
        );

        $route = new Route(Route::GET, ObjectRoute::OBJECT_STR, $prefix.$selectorRegex, $objectRouteActions);
        $this->registerRoute($route);
    }

    /**
     * Prepare and return a route action
     *
     * @param ServerRequestInterface $request Request
     * @param ActionRouteInterface $route Route
     * @return ActionInterface|Callable $action Action
     */
    public function getRouteAction(ServerRequestInterface $request, ActionRouteInterface $route)
    {
        return $this->routerContainer->getRouteAction($request, $route);
    }
}
