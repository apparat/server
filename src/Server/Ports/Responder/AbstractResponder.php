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

use Apparat\Kernel\Ports\Kernel;
use Apparat\Server\Application\Factory\PayloadFactory;
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
     * Action name
     *
     * @var string
     */
    const ACTION = 'Default/Abstract';
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
     * Run the responder and process the payload
     *
     * @param PayloadInterface $payload Domain payload
     * @return ResponseInterface Response
     * @see https://github.com/pmjones/adr/blob/master/example-code/Web/AbstractResponder.php
     * @see https://github.com/pmjones/adr/blob/master/example-code/Web/Blog/Responder/BlogBrowseResponder.php
     */
    public function __invoke(PayloadInterface $payload)
    {
        $payloadClass = (new \ReflectionClass($payload))->getShortName();
        $method = strtolower($payloadClass);

        // If there's no processor for this type of payload
        if (!is_callable([$this, $method])) {
            /** @var PayloadFactory $payloadFactory */
            $payloadFactory = Kernel::create(PayloadFactory::class);
            $error = $payloadFactory->error(500, sprintf('Unrecognized payload type "%s"', $payloadClass));
            return $this->error($error);
        }

        return $this->$method($payload);
    }

    /**
     * Process an error payload
     *
     * @param PayloadInterface $payload Error payload
     * @return ResponseInterface Response
     */
    protected function error(PayloadInterface $payload)
    {
        // Ensure the error template is used
        $this->view->setAction('Error/'.(new \ReflectionClass($payload))->getShortName());

        // Set the HTTP status code
        $this->response = $this->response->withStatus($payload->get('status'), $payload->get('description'));

        // Add HTTP headers
        foreach ((array)$payload->get('header') as $name => $value) {
            $this->response = $this->response->withHeader($name, $value);
        }

        $this->view->assign('error', $payload->get());
        $this->response->getBody()->write($this->view->render());
        return $this->response;
    }
}
