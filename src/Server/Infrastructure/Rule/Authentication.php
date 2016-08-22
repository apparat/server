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

namespace Apparat\Server\Infrastructure\Rule;

use Apparat\Server\Ports\Authenticator\AuthenticatorInterface;
use Apparat\Server\Ports\Authenticator\InvalidArgumentException;
use Aura\Router\Route;
use Aura\Router\Rule\RuleInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Authentication rule
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class Authentication implements RuleInterface
{
    /**
     * Check if the request matches the required authentication state
     *
     * @param ServerRequestInterface $request HTTP request
     * @param Route $route Route
     * @return boolean The request matches the required authentication state
     * @throw InvalidArgumentException If the provided authenticator is invalid
     */
    public function __invoke(ServerRequestInterface $request, Route $route)
    {
        // If no authentication is required for this route
        $auth = $route->auth;
        if (!is_array($auth)) {
            return true;
        }

        // Run through all authentication possibilities
        foreach (array_values($auth) as $index => $authenticator) {
            // If the provided authenticator is invalid
            if (!($authenticator instanceof AuthenticatorInterface)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid authenticator at index %s', $index),
                    InvalidArgumentException::INVALID_AUTHENTICATOR
                );
            }

            // Try to authenticate the request
            if ($authenticator->authenticate($request) === true) {
                return true;
            }
        }

        // Request is not authenticated
        return false;
    }
}
