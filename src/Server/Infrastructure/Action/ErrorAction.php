<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Ports\Action
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

namespace Apparat\Server\Infrastructure\Action;

use Apparat\Server\Ports\Action\AbstractAction;
use Psr\Http\Message\ResponseInterface;

/**
 * Object action
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class ErrorAction extends AbstractAction
{
    /**
     * Run the action
     *
     * @return ResponseInterface Response
     */
    public function __invoke()
    {
        // TODO: Implement __invoke() method.

        // TODO Error responder
//        // Instantiate a response
//        $response = Kernel::create(ResponseInterface::class);
//
//        // Get the first of the best-available non-matched routes
//        $failedRoute = $matcher->getFailedRoute();
//
//        // Which matching rule failed?
//        switch ($failedRoute->failedRule) {
//            case 'Aura\Router\Rule\Allows':
//                // 405 METHOD NOT ALLOWED
//                // Send the $failedRoute->allows as 'Allow:'
//                break;
//            case 'Aura\Router\Rule\Accepts':
//                // 406 NOT ACCEPTABLE
//                break;
//            default:
//                // 404 NOT FOUND
//                break;
//        }
//
//        return $response;
    }
}
