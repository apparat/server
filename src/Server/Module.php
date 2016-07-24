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
use Apparat\Server\Domain\Contract\RouterContainerInterface;
use Apparat\Server\Domain\Model\Server;
use Apparat\Server\Domain\Service\AbstractService;
use Apparat\Server\Infrastructure\Route\AuraRouterAdapter;
use Apparat\Server\Infrastructure\Traits\DateActionsTrait;
use Apparat\Server\Infrastructure\Traits\ObjectActionsTrait;
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
     * Use external dependency configurations
     */
    use DateActionsTrait, ObjectActionsTrait;
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
            'shared' => false,
            'substitutions' => [
                RouterContainerInterface::class => [
                    'instance' => AuraRouterAdapter::class,
                ]
            ]
        ]);

        // Configure the router
        $diContainer->register(RouterContainer::class, [
            'constructParams' => [
                rtrim(parse_url(getenv('APPARAT_BASE_URL'), PHP_URL_PATH), '/') ?: null
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

        // Configure the date action dependencies
        $this->configureDateActionDependencies($diContainer);

        // Configure the object action dependencies
        $this->configureObjectActionDependencies($diContainer);
    }
}
