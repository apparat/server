<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server
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

namespace Apparat\Server;

use Apparat\Kernel\Ports\AbstractModule;
use Apparat\Kernel\Ports\Contract\DependencyInjectionContainerInterface;
use Apparat\Server\Application\Factory\PayloadFactory;
use Apparat\Server\Domain\Contract\PayloadFactoryInterface;
use Apparat\Server\Domain\Contract\ResponderInterface;
use Apparat\Server\Domain\Contract\RouterContainerInterface;
use Apparat\Server\Domain\Model\Server;
use Apparat\Server\Domain\Service\AbstractService;
use Apparat\Server\Domain\Service\ServiceInterface;
use Apparat\Server\Infrastructure\Action\DayAction;
use Apparat\Server\Infrastructure\Action\ErrorAction;
use Apparat\Server\Infrastructure\Action\HourAction;
use Apparat\Server\Infrastructure\Action\MinuteAction;
use Apparat\Server\Infrastructure\Action\MonthAction;
use Apparat\Server\Infrastructure\Action\ObjectAction;
use Apparat\Server\Infrastructure\Action\ObjectsAction;
use Apparat\Server\Infrastructure\Action\SecondAction;
use Apparat\Server\Infrastructure\Action\TypeAction;
use Apparat\Server\Infrastructure\Action\YearAction;
use Apparat\Server\Infrastructure\Responder\DayResponder;
use Apparat\Server\Infrastructure\Responder\ErrorResponder;
use Apparat\Server\Infrastructure\Responder\HourResponder;
use Apparat\Server\Infrastructure\Responder\MinuteResponder;
use Apparat\Server\Infrastructure\Responder\MonthResponder;
use Apparat\Server\Infrastructure\Responder\ObjectResponder;
use Apparat\Server\Infrastructure\Responder\ObjectsResponder;
use Apparat\Server\Infrastructure\Responder\SecondResponder;
use Apparat\Server\Infrastructure\Responder\TypeResponder;
use Apparat\Server\Infrastructure\Responder\YearResponder;
use Apparat\Server\Infrastructure\Route\AuraRouterAdapter;
use Apparat\Server\Infrastructure\Service\DayService;
use Apparat\Server\Infrastructure\Service\ErrorService;
use Apparat\Server\Infrastructure\Service\HourService;
use Apparat\Server\Infrastructure\Service\MinuteService;
use Apparat\Server\Infrastructure\Service\MonthService;
use Apparat\Server\Infrastructure\Service\ObjectService;
use Apparat\Server\Infrastructure\Service\ObjectsService;
use Apparat\Server\Infrastructure\Service\SecondService;
use Apparat\Server\Infrastructure\Service\TypeService;
use Apparat\Server\Infrastructure\Service\YearService;
use Apparat\Server\Ports\Responder\AbstractResponder;
use Apparat\Server\Ports\View\TYPO3FluidView;
use Apparat\Server\Ports\View\ViewInterface;
use Aura\Router\RouterContainer;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;
use TYPO3Fluid\Fluid\View\AbstractTemplateView;
use Zend\Diactoros\Response;

/**
 * Object module
 *
 * @package Apparat\Object
 * @subpackage Apparat\Object
 */
class Module extends AbstractModule
{
    /**
     * Module name
     *
     * @var string
     */
    const NAME = 'server';

    /**
     * Validate the environment
     *
     * @param Dotenv $environment Environment
     */
    protected static function validateEnvironment(Dotenv $environment)
    {
        parent::validateEnvironment($environment);

        // Validate the required environment variables
//        $environment->required('APPARAT_BASE_URL')->notEmpty();
//        $environment->required('OBJECT_DATE_PRECISION')->isInteger()->allowedValues([0, 1, 2, 3, 4, 5, 6]);
    }

    /**
     * Configure the dependency injection container
     *
     * @param DependencyInjectionContainerInterface $diContainer Dependency injection container
     * @return void
     */
    public function configureDependencyInjection(DependencyInjectionContainerInterface $diContainer)
    {
        parent::configureDependencyInjection($diContainer);

        // Configure the server
        $diContainer->register(Server::class, [
            'shared' => true,
            'substitutions' => [
                RouterContainerInterface::class => [
                    'instance' => AuraRouterAdapter::class,
                ]
            ]
        ]);

        // Configure the router
        $diContainer->register(RouterContainer::class, [
            'constructParams' => [
                parse_url(getenv('APPARAT_BASE_URL'), PHP_URL_PATH) ?: null
            ]
        ]);

        // Configure the service
        $diContainer->register(AbstractService::class, [
            'substitutions' => [
                PayloadFactoryInterface::class => [
                    'instance' => PayloadFactory::class,
                ]
            ]
        ]);

        // Configure the responder: Diactoros Response and TYPO3Fluid template view
        $diContainer->register(AbstractResponder::class, [
            'substitutions' => [
                ResponseInterface::class => [
                    'instance' => Response::class,
                ],
                ViewInterface::class => [
                    'instance' => TYPO3FluidView::class,
                ]
            ]
        ]);

        // Configure the TYPO3Fluid template view
        $diContainer->register(AbstractTemplateView::class, [
            'constructParams' => [null]
        ]);

        // Configure the action dependencies
        $this->configureActionDependencies($diContainer);
    }

    /**
     * Configure the action dependencies
     *
     * @param DependencyInjectionContainerInterface $diContainer Dependency injection container
     */
    protected function configureActionDependencies(DependencyInjectionContainerInterface $diContainer)
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
