<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\<Layer>
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

namespace Apparat\Server\Domain\Contract;

/**
 * Route interface
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Domain
 */
interface RouteInterface
{
    /**
     * Route constructor
     *
     * @param string|array $verbs Allowed HTTP verbs
     * @param string $name Route name
     * @param string $path Route path
     * @param string|callable $action Route action
     */
    public function __construct($verbs, $name, $path, $action);

    /**
     * Get the route name
     *
     * @return string Route name
     */
    public function getName();

    /**
     * Get the route path
     *
     * @return string
     */
    public function getPath();

    /**
     * Get the route action
     *
     * @return callable|string Route action
     */
    public function getAction();

    /**
     * Get the route tokens
     *
     * @return array Route tokens
     */
    public function getTokens();

    /**
     * Set the route tokens
     *
     * @param array $tokens Route tokens
     * @return RouteInterface Self reference
     */
    public function setTokens(array $tokens);

    /**
     * Get the route defaults
     *
     * @return array Route defaults
     */
    public function getDefaults();

    /**
     * Set the route defaults
     *
     * @param array $defaults Route defaults
     * @return RouteInterface Self reference
     */
    public function setDefaults(array $defaults);

    /**
     * Set the route wildcard name
     *
     * @return null|string
     */
    public function getWildcard();

    /**
     * Get the route wildcard name
     *
     * @param null|string $wildcard Wildcard name
     * @return RouteInterface Self reference
     */
    public function setWildcard($wildcard);

    /**
     * Get the route host
     *
     * @return null|string Route host
     */
    public function getHost();

    /**
     * Set the route host
     *
     * @param null|string $host Route host
     * @return RouteInterface Self reference
     */
    public function setHost($host);

    /**
     * Get the allowed Accept headers
     *
     * @return array Allowed Accept headers
     */
    public function getAccepts();

    /**
     * Set the allowed Accept headers
     *
     * @param array $accepts Allowed Accept headers
     * @return RouteInterface Self reference
     */
    public function setAccepts(array $accepts);

    /**
     * Get the allowed HTTP verbs
     *
     * @return array Allowed HTTP verbs
     */
    public function getVerbs();

    /**
     * Set the allowed HTTP verbs
     *
     * @param array $verbs Allowed HTTP verbs
     * @return RouteInterface Self reference
     */
    public function setVerbs(array $verbs);

    /**
     * Get the secure protocol mode
     *
     * @return boolean|null Secure protocol mode
     */
    public function getSecure();

    /**
     * Set the secure protocol mode
     *
     * @param boolean|null $secure Secure protocol mode
     * @return RouteInterface Self reference
     */
    public function setSecure($secure);

    /**
     * Get the authentication information
     *
     * @return mixed Authentication information
     */
    public function getAuth();

    /**
     * Set the authentication information
     *
     * @param mixed $auth Authentication information
     * @return RouteInterface Self reference
     */
    public function setAuth($auth);

    /**
     * Get the custom extra information
     *
     * @return mixed Custom extra information
     */
    public function getExtras();

    /**
     * Set the custom extra information
     *
     * @param array $extras Custom extra information
     * @return RouteInterface Self reference
     */
    public function setExtras(array $extras);
}
