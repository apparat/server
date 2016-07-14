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

namespace Apparat\Server\Ports\Responder;

use Apparat\Server\Domain\Payload\PayloadInterface;
use Apparat\Server\Ports\View\ViewInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstract responder
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
abstract class AbstractResponder implements ResponderInterface
{
    /**
     * View
     *
     * @var ViewInterface
     */
    protected $view;
    /**
     * Response
     *
     * @var ResponseInterface
     */
    protected $response;
    /**
     * Action name
     *
     * @var string
     */
    const ACTION = 'Abstract';

    /**
     * Constructor
     *
     * @param ResponseInterface $response
     * @param ViewInterface $view
     */
    public function __construct(ResponseInterface $response, ViewInterface $view)
    {
        $this->response = $response;
        $this->view = $view->setAction(static::ACTION);
    }

    /**
     * Run the responder
     *
     * @param PayloadInterface $payload Domain payload
     * @return ResponseInterface Response
     * @see https://github.com/pmjones/adr/blob/master/example-code/Web/AbstractResponder.php
     * @see https://github.com/pmjones/adr/blob/master/example-code/Web/Blog/Responder/BlogBrowseResponder.php
     */
    public function __invoke(PayloadInterface $payload)
    {
        $this->view->assign('objects', $payload->get());
        $this->response->getBody()->write($this->view->render());
        $this->response->getBody()->rewind();
        return $this->response;
    }
}
