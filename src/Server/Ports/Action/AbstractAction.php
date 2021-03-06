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

namespace Apparat\Server\Ports\Action;

use Apparat\Server\Domain\Contract\ResponderInterface;
use Apparat\Server\Domain\Service\ServiceInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstract action
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
abstract class AbstractAction implements ActionInterface
{
    /**
     * Server request
     *
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * Domain service
     *
     * @var ServiceInterface
     */
    protected $domain;
    /**
     * Responder
     *
     * @var ResponderInterface
     */
    protected $responder;
    /**
     * Action parameters
     *
     * @var array
     */
    protected $params;

    /**
     * Constructor
     *
     * @param ServerRequestInterface $request Server request
     * @param ServiceInterface $domain Domain service
     * @param ResponderInterface $responder Responder
     */
    public function __construct(
        ServerRequestInterface $request,
        ServiceInterface $domain,
        ResponderInterface $responder
    ) {
        $this->request = $request;
        $this->domain = $domain;
        $this->responder = $responder;
    }

    /**
     * Set action parameters
     *
     * @param array $params Action Parameters
     * @return ActionInterface Self reference
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }
}
