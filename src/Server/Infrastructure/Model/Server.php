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
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\ObjectsAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Infrastructure\Route\AbstractActionRoute;
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
     * View resources
     *
     * @var array
     */
    protected $viewResources = [];

    /**
     * Enable the object route for a particular repository
     *
     * @param string $repositoryPath Repository path
     * @param int $enable Enable / disable default actions
     */
    public function enableObjectRoute($repositoryPath = '', $enable = ObjectRoute::ALL)
    {
        $routeName = ObjectRoute::OBJECT_STR;
        $datePrecision = intval(getenv('OBJECT_DATE_PRECISION'));
        $selectorRegex = SelectorFactory::getSelectorRegex($datePrecision);
        $objectRouteActions = array_filter(
            array_merge(
                self::getEnabledObjectActionClasses($enable),
                self::getEnabledDateActionClasses($enable, $datePrecision)
            )
        );

        // Add a repository sub-pattern
        if (strlen($repositoryPath)) {
            $selectorRegex = '(?:/(?P<repository>.+?))'.$selectorRegex;
            $routeName .= '.'.$repositoryPath;
        }

        $route = new Route(Route::GET, $routeName, $selectorRegex, $objectRouteActions);
        $route->setAccepts(ObjectRoute::$accept);
        $route->setObject(true);
        $this->registerRoute($route);
    }

    /**
     * Return the enabled object action classes
     *
     * @param int $enable Enable / disable default actions
     * @return array Enabled object action classes
     */
    protected function getEnabledObjectActionClasses($enable)
    {
        return [
            ObjectRoute::OBJECT_STR => (ObjectRoute::OBJECT & $enable) ? ObjectAction::class : null,
            ObjectRoute::TYPE_STR => (ObjectRoute::TYPE & $enable) ? TypeAction::class : null,
            ObjectRoute::OBJECTS_STR => (ObjectRoute::OBJECTS & $enable) ? ObjectsAction::class : null,
        ];
    }

    /**
     * Return the enabled date action classes
     *
     * @param int $enable Enable / disable default actions
     * @param int $datePrecision Date precision
     * @return array Enabled date action classes
     */
    protected function getEnabledDateActionClasses($enable, $datePrecision)
    {
        return array_slice(
            [
                ObjectRoute::SECOND_STR => (ObjectRoute::SECOND & $enable) ? SecondAction::class : null,
                ObjectRoute::MINUTE_STR => (ObjectRoute::MINUTE & $enable) ? MinuteAction::class : null,
                ObjectRoute::HOUR_STR => (ObjectRoute::HOUR & $enable) ? HourAction::class : null,
                ObjectRoute::DAY_STR => (ObjectRoute::DAY & $enable) ? DayAction::class : null,
                ObjectRoute::MONTH_STR => (ObjectRoute::MONTH & $enable) ? MonthAction::class : null,
                ObjectRoute::YEAR_STR => (ObjectRoute::YEAR & $enable) ? YearAction::class : null,
            ],
            6 - $datePrecision
        );
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
        return $this->routerContainer->getRouteAction($request, $route);
    }

    /**
     * Return view resources
     *
     * @param string|null $name Optional: view resource name
     * @return array View resources
     */
    public function getViewResources($name = null)
    {
        return ($name === null) ?
            $this->viewResources :
            (array_key_exists($name, $this->viewResources) ? $this->viewResources[$name] : null);
    }

    /**
     * Set the view resources
     *
     * @param array $viewResources View resources
     */
    public function setViewResources(array $viewResources)
    {
        $this->viewResources = $viewResources;
    }
}
