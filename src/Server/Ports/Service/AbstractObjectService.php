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

namespace Apparat\Server\Ports\Service;

use Apparat\Object\Ports\Facades\RepositoryFacade;
use Apparat\Object\Ports\Repository\SelectorInterface;
use Apparat\Server\Domain\Payload\PayloadInterface;

/**
 * Abstract object service
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class AbstractObjectService extends AbstractService
{
    /**
     * Find an object by select
     *
     * @param string $repository Repository identifier
     * @param SelectorInterface $selector Object selector
     * @return PayloadInterface Payload
     */
    public function findObject($repository, SelectorInterface $selector)
    {
        $objects = RepositoryFacade::instance($repository)->findObjects($selector);

        // If exactly one object was found
        if (count($objects) == 1) {
            return $this->payloadFactory->found([current($objects)]);
        }

        // If no object was found
        return $this->payloadFactory->error(404, 'Not found');
    }
}
