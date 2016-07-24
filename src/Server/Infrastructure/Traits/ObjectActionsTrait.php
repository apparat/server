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
use Apparat\Server\Infrastructure\Action\ErrorAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\ObjectsAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Responder\ErrorResponder;
use Apparat\Server\Infrastructure\Responder\ObjectResponder;
use Apparat\Server\Infrastructure\Responder\ObjectsResponder;
use Apparat\Server\Infrastructure\Responder\TypeResponder;
use Apparat\Server\Infrastructure\Service\ErrorService;
use Apparat\Server\Infrastructure\Service\ObjectService;
use Apparat\Server\Infrastructure\Service\ObjectsService;
use Apparat\Server\Infrastructure\Service\TypeService;

/**
 * Object actions trait
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
trait ObjectActionsTrait
{
    /**
     * Configure the object action dependencies
     *
     * @param DependencyInjectionContainerInterface $diContainer Dependency injection container
     */
    protected function configureObjectActionDependencies(DependencyInjectionContainerInterface $diContainer)
    {
        $diContainer->register(ObjectAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => ObjectService::class,
                ],
                ResponderInterface::class => [
                    'instance' => ObjectResponder::class,
                ]
            ]
        ]);
        $diContainer->register(ObjectsAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => ObjectsService::class,
                ],
                ResponderInterface::class => [
                    'instance' => ObjectsResponder::class,
                ]
            ]
        ]);
        $diContainer->register(TypeAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => TypeService::class,
                ],
                ResponderInterface::class => [
                    'instance' => TypeResponder::class,
                ]
            ]
        ]);
        $diContainer->register(ErrorAction::class, [
            'substitutions' => [
                ServiceInterface::class => [
                    'instance' => ErrorService::class,
                ],
                ResponderInterface::class => [
                    'instance' => ErrorResponder::class,
                ]
            ]
        ]);
    }
}
