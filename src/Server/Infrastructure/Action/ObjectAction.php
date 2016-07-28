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

use Apparat\Object\Ports\Factory\SelectorFactory;
use Apparat\Server\Ports\Action\AbstractSelectorAction;
use Apparat\Server\Ports\Responder\AbstractObjectResponder;
use Apparat\Server\Ports\Service\AbstractObjectService;
use Apparat\Server\Ports\Types\ObjectRoute;
use Psr\Http\Message\ResponseInterface;

/**
 * Object action
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class ObjectAction extends AbstractSelectorAction
{
    /**
     * Domain service
     *
     * @var AbstractObjectService
     */
    protected $domain;
    /**
     * Responder
     *
     * @var AbstractObjectResponder
     */
    protected $responder;

    /**
     * Check whether a set of attributes matches the action requirements
     *
     * @param array $attributes Attributes
     * @return boolean The attributes match the action requirements
     */
    public static function matches(array $attributes)
    {
        return self::notEmptyDateSelector($attributes, 6)
        && !empty($attributes[ObjectRoute::ID_STR])
        && ($attributes[ObjectRoute::ID_STR] !== ObjectRoute::WILDCARD)
        && (empty($attributes[ObjectRoute::REVISION_STR])
            || ($attributes[ObjectRoute::REVISION_STR] !== ObjectRoute::WILDCARD));
    }

    /**
     * Run the action
     *
     * @return ResponseInterface Response
     */
    public function __invoke()
    {
        $selector = SelectorFactory::createFromParams($this->request->getAttributes());
        $payload = $this->domain->findObject(strval($this->request->getAttribute('repository')), $selector);
        return $this->responder->__invoke($payload);
    }
}
