<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Ports
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

namespace Apparat\Server\Ports\Route;

use Apparat\Server\Ports\Action\ActionInterface;
use Apparat\Server\Ports\Contract\RouteInterface;

/**
 * Route
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class Route implements RouteInterface
{
    /**
     * GET request
     *
     * @var string
     */
    const GET = 'GET';
    /**
     * POST request
     *
     * @var string
     */
    const POST = 'POST';
    /**
     * PATCH request
     *
     * @var string
     */
    const PATCH = 'PATCH';
    /**
     * DELETE request
     *
     * @var string
     */
    const DELETE = 'DELETE';
    /**
     * OPTIONS request
     *
     * @var string
     */
    const OPTIONS = 'OPTIONS';
    /**
     * HEAD request
     *
     * @var string
     */
    const HEAD = 'HEAD';

    /**
     * Allowed HTTP verbs
     *
     * @var array
     */
    protected static $validVerbs = [
        self::GET => true,
        self::POST => true,
        self::PATCH => true,
        self::DELETE => true,
        self::OPTIONS => true,
        self::HEAD => true
    ];
    /**
     * Route name
     *
     * @var string
     */
    protected $name;
    /**
     * Route path
     *
     * @var string
     */
    protected $path;
    /**
     * Route action
     *
     * @var string|callable
     */
    protected $action;
    /**
     * Route tokens
     *
     * @var array
     */
    protected $tokens = [];
    /**
     * Route default values
     *
     * @var array
     */
    protected $defaults = [];
    /**
     * Route wildcard name
     *
     * @var string|null
     */
    protected $wildcard = null;
    /**
     * Route host
     *
     * @var string|null
     */
    protected $host = null;
    /**
     * Allowed accept headers
     *
     * @var array
     */
    protected $accepts = [];
    /**
     * Allowed HTTP verbs
     *
     * @var array
     */
    protected $verbs = [];
    /**
     * Secure protocol behaviour
     *
     * @var boolean|null
     */
    protected $secure = false;
    /**
     * Authentication parameters
     *
     * @var mixed
     */
    protected $auth = null;
    /**
     * Custom extra parameters
     *
     * @var array
     */
    protected $extras = [];
    /**
     * Object route
     *
     * @var bool
     */
    protected $object = false;

    /**
     * Route constructor
     *
     * @param string|array $verbs Allowed HTTP verbs
     * @param string $name Route name
     * @param string $path Route path
     * @param string|\Callable|array $action Route action
     */
    public function __construct($verbs, $name, $path, $action)
    {
        // Set whether this is an object route
        $this->object = is_array($action);

        // Set and validate the allowed HTTP verbs
        $this->setAndValidateVerbs($verbs);

        // Set and validate the route name
        $this->setAndValidateName($name);

        // Set and validate the route path
        $this->setAndValidatePath($path);

        // Set and validate the route action
        $this->setAndValidateActionList($action);
    }

    /**
     * Validate the allowed HTTP verbs
     *
     * @param string|array $verbs Allowed HTTP verbs
     * @throws InvalidArgumentException If the HTTP verb list is empty
     * @throws InvalidArgumentException If the HTTP verb is invalid
     */
    protected function setAndValidateVerbs($verbs)
    {
        $this->verbs = array_map('strtoupper', array_filter((array)$verbs));

        // If the HTTP verb list is empty
        if (!count($this->verbs)) {
            throw new InvalidArgumentException(
                'Empty route HTTP verbs',
                InvalidArgumentException::EMPTY_ROUTE_HTTP_VERBS
            );
        }

        // Run through all registered HTTP verbs
        foreach ($this->verbs as $verb) {
            // If the HTTP verb is invalid
            if (!array_key_exists($verb, self::$validVerbs)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid route HTTP verb "%s"', $verb),
                    InvalidArgumentException::INVALID_ROUTE_HTTP_VERB
                );
            }
        }
    }

    /**
     * Set and validate the route name
     *
     * @param string $name Route name
     * @throws InvalidArgumentException If the route name is empty
     */
    protected function setAndValidateName($name)
    {
        $this->name = trim($name);
        // If the route name is empty
        if (!strlen($this->name)) {
            throw new InvalidArgumentException(
                'Route name must not be empty',
                InvalidArgumentException::EMPTY_ROUTE_NAME
            );
        }
    }

    /**
     * Set and validate the route path
     *
     * @param string $path Route path
     * @throws InvalidArgumentException If the route path is empty
     */
    protected function setAndValidatePath($path)
    {
        $this->path = trim($path);
        // If the route path is empty
        if (!strlen($this->path)) {
            throw new InvalidArgumentException(
                'Route path must not be empty',
                InvalidArgumentException::EMPTY_ROUTE_NAME
            );
        }
    }

    /**
     * Set and validate the route action
     *
     * @param string|\Callable|array $actions Route actions
     */
    protected function setAndValidateActionList($actions)
    {
        array_map([$this, 'setAndValidateAction'], (array)$actions);
        $this->action = $actions;
    }

    /**
     * Get the route name
     *
     * @return string Route name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the route path
     *
     * @return string Route path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the route action
     *
     * @return callable|string Route action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get the route tokens
     *
     * @return array Route tokens
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Set the route tokens
     *
     * @param array $tokens Route tokens
     * @return Route Self reference
     */
    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
        return $this;
    }

    /**
     * Get the route defaults
     *
     * @return array Route defaults
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Set the route defaults
     *
     * @param array $defaults Route defaults
     * @return Route Self reference
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
        return $this;
    }

    /**
     * Set the route wildcard name
     *
     * @return null|string
     */
    public function getWildcard()
    {
        return $this->wildcard;
    }

    /**
     * Get the route wildcard name
     *
     * @param null|string $wildcard Wildcard name
     * @return Route Self reference
     */
    public function setWildcard($wildcard)
    {
        $this->wildcard = $wildcard;
        return $this;
    }

    /**
     * Get the route host
     *
     * @return null|string Route host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the route host
     *
     * @param null|string $host Route host
     * @return Route Self reference
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get the allowed Accept headers
     *
     * @return array Allowed Accept headers
     */
    public function getAccepts()
    {
        return $this->accepts;
    }

    /**
     * Set the allowed Accept headers
     *
     * @param array $accepts Allowed Accept headers
     * @return Route Self reference
     */
    public function setAccepts(array $accepts)
    {
        $this->accepts = $accepts;
        return $this;
    }

    /**
     * Get the allowed HTTP verbs
     *
     * @return array Allowed HTTP verbs
     */
    public function getVerbs()
    {
        return $this->verbs;
    }

    /**
     * Set the allowed HTTP verbs
     *
     * @param array $verbs Allowed HTTP verbs
     * @return Route Self reference
     */
    public function setVerbs(array $verbs)
    {
        $this->verbs = $verbs;
        return $this;
    }

    /**
     * Get the secure protocol mode
     *
     * @return boolean|null Secure protocol mode
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Set the secure protocol mode
     *
     * @param boolean|null $secure Secure protocol mode
     * @return Route Self reference
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
        return $this;
    }

    /**
     * Get the authentication information
     *
     * @return mixed Authentication information
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set the authentication information
     *
     * @param mixed $auth Authentication information
     * @return Route Self reference
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * Get the custom extra information
     *
     * @return array Custom extra information
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * Set the custom extra information
     *
     * @param array $extras Custom extra information
     * @return Route Self reference
     */
    public function setExtras(array $extras)
    {
        $this->extras = $extras;
        return $this;
    }

    /**
     * Return whether this is an object route
     *
     * @return boolean Object route
     */
    public function isObject()
    {
        return $this->object;
    }

    /**
     * Set and validate the route action
     *
     * @param string $action Route action
     * @throws InvalidArgumentException If the route action is empty
     * @throws InvalidArgumentException If the route action is not a class name
     * @throws InvalidArgumentException If the route action is neither a callable nor an ActionInterface
     */
    protected function setAndValidateAction($action)
    {
        // If the action is given as string
        if (is_string($action)) {
            $this->action = trim($action);

            // If the route action is empty
            if (!strlen($this->action)) {
                throw new InvalidArgumentException(
                    'Route action must not be empty',
                    InvalidArgumentException::EMPTY_ROUTE_ACTION
                );
            }

            // If the route action is not a class name
            if (!class_exists($this->action)) {
                throw new InvalidArgumentException(
                    'Route action must be an existing class name',
                    InvalidArgumentException::ROUTE_ACTION_MUST_BE_CLASSNAME
                );
            }

            // If the route action doesn't implement the ActionInterface
            $actionReflection = new \ReflectionClass($this->action);
            if (!$actionReflection->implementsInterface(ActionInterface::class)) {
                throw new InvalidArgumentException(
                    'Route action must implement '.ActionInterface::class,
                    InvalidArgumentException::ROUTE_ACTION_MUST_IMPLEMENT_ACTION_INTERFACE
                );
            }

            return;
        }

        // If the action is given as callable
        if (is_callable($action)) {
            return;
        }

        // If the route action is neither a callable nor an ActionInterface
        throw new InvalidArgumentException(
            'Route action must be a callable or '.ActionInterface::class,
            InvalidArgumentException::ROUTE_ACTION_NOT_CALLABLE_OR_ACTION_INTERFACE
        );
    }
}
