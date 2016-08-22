<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Ports
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

namespace Apparat\Server\Ports\Authenticator;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstract bearer authenticator
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 * @see https://tools.ietf.org/html/rfc6750#section-2
 */
abstract class AbstractBearer implements AuthenticatorInterface
{
    /**
     * Authenticate a request
     *
     * @param ServerRequestInterface $request Request
     * @return boolean Request is authenticated
     * @see https://quill.p3k.io/creating-a-micropub-endpoint#verifying-access-tokens
     */
    public function authenticate(ServerRequestInterface $request)
    {
        return $this->authenticateHeader($request)
        || $this->authenticateBody($request)
        || $this->authenticateQuery($request);
    }

    /**
     * Authenticate with an "Authorization" header
     *
     * @param ServerRequestInterface $request Request
     * @return bool Request is valid
     */
    protected function authenticateHeader(ServerRequestInterface $request)
    {
        // Run through all "Authorization" headers
        foreach ($request->getHeader('Authorization') as $authHeader) {

            // If this is supposed to be a bearer token
            if (!strncmp(strtolower($authHeader), 'bearer', 6)) {
                $bearerToken = preg_split('%\s+%', $authHeader);

                // If there is really a bearer token
                if (count($bearerToken) > 1) {
                    return $this->verifyToken($bearerToken[1]);
                }
            }
        }

        return false;
    }

    /**
     * Verify the validity of the bearer token
     *
     * @param string $token Bearer token
     * @return boolean The bearer token is valid
     */
    abstract protected function verifyToken($token);

    /**
     * Authenticate with an "access_token" body parameter
     *
     * @param ServerRequestInterface $request Request
     * @return bool Request is valid
     */
    protected function authenticateBody(ServerRequestInterface $request)
    {
        $bodyParameters = (array)$request->getParsedBody();
        return array_key_exists('access_token', $bodyParameters) ?
            $this->verifyToken($bodyParameters['access_token']) : false;
    }

    /**
     * Authenticate with an "access_token" query parameter
     *
     * @param ServerRequestInterface $request Request
     * @return bool Request is valid
     */
    protected function authenticateQuery(ServerRequestInterface $request)
    {
        $queryParameters = (array)$request->getQueryParams();
        return array_key_exists('access_token', $queryParameters) ?
            $this->verifyToken($queryParameters['access_token']) : false;
    }
}
