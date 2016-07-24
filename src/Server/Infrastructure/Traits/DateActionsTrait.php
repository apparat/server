<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure
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

namespace Apparat\Server\Infrastructure\Traits;

use Apparat\Kernel\Ports\Contract\DependencyInjectionContainerInterface;
use Apparat\Server\Domain\Contract\ResponderInterface;
use Apparat\Server\Domain\Service\ServiceInterface;
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Infrastructure\Responder\DayResponder;
use Apparat\Server\Infrastructure\Responder\HourResponder;
use Apparat\Server\Infrastructure\Responder\MinuteResponder;
use Apparat\Server\Infrastructure\Responder\MonthResponder;
use Apparat\Server\Infrastructure\Responder\SecondResponder;
use Apparat\Server\Infrastructure\Responder\YearResponder;
use Apparat\Server\Infrastructure\Service\DayService;
use Apparat\Server\Infrastructure\Service\HourService;
use Apparat\Server\Infrastructure\Service\MinuteService;
use Apparat\Server\Infrastructure\Service\MonthService;
use Apparat\Server\Infrastructure\Service\SecondService;
use Apparat\Server\Infrastructure\Service\YearService;

/**
 * Date actions trait
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
trait DateActionsTrait
{
    /**
     * Configure the action dependencies
     *
     * @param DependencyInjectionContainerInterface $diContainer Dependency injection container
     */
    protected function configureDateActionDependencies(DependencyInjectionContainerInterface $diContainer)
    {
        $diContainer->register(YearAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => YearService::class,
                ],
                ResponderInterface::class => [
                    'instance' => YearResponder::class,
                ]
            ]
        ]);
        $diContainer->register(MonthAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => MonthService::class,
                ],
                ResponderInterface::class => [
                    'instance' => MonthResponder::class,
                ]
            ]
        ]);
        $diContainer->register(DayAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => DayService::class,
                ],
                ResponderInterface::class => [
                    'instance' => DayResponder::class,
                ]
            ]
        ]);
        $diContainer->register(HourAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => HourService::class,
                ],
                ResponderInterface::class => [
                    'instance' => HourResponder::class,
                ]
            ]
        ]);
        $diContainer->register(MinuteAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => MinuteService::class,
                ],
                ResponderInterface::class => [
                    'instance' => MinuteResponder::class,
                ]
            ]
        ]);
        $diContainer->register(SecondAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => SecondService::class,
                ],
                ResponderInterface::class => [
                    'instance' => SecondResponder::class,
                ]
            ]
        ]);
    }
}
