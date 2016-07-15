<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure\Service
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

namespace Apparat\Server\Infrastructure\Service;

use Apparat\Server\Domain\Payload\PayloadInterface;
use Apparat\Server\Ports\Service\AbstractService;

/**
 * Error result service
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Domain
 */
class ErrorService extends AbstractService
{
    /**
     * Explain a failed request with an appropriate error message
     *
     * @param array $attributes Request attributes
     * @return PayloadInterface Payload
     */
    public function explain(array $attributes)
    {
        // Which matching rule failed?
        switch ($attributes['failure']) {
            // Invalid object path
            case 'Apparat\Server\Infrastructure\Route\ObjectPath':
                return $this->payloadFactory->error(404, 'Bad apparat object request');
                break;

            // Invalid method
            case 'Aura\Router\Rule\Allows':
                return $this->payloadFactory->error(
                    405,
                    'Method not allowed',
                    ['Allow' => $attributes['allow']]
                );
                break;

            // Response not acceptable
            case 'Aura\Router\Rule\Accepts':
                return $this->payloadFactory->error(
                    406,
                    'Not acceptable',
                    ['Accept' => $attributes['accept']]
                );
                break;
        }

        return $this->payloadFactory->error(
            404,
            'Not found'
        );
    }
}
