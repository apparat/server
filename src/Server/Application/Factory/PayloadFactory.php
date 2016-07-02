<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Application
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

namespace Apparat\Server\Application\Factory;

use Apparat\Kernel\Ports\Kernel;
use Apparat\Server\Application\Payload\Error;
use Apparat\Server\Application\Payload\Found;
use Apparat\Server\Domain\Contract\PayloadFactoryInterface;

/**
 * Payload factory
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Application
 */
class PayloadFactory implements PayloadFactoryInterface
{
    /**
     * Create a new Found payload
     *
     * @param array $payload Payload properties
     * @return Found Found payload
     */
    public function found(array $payload)
    {
        return Kernel::create(Found::class, [$payload]);
    }

    /**
     * Create a new Error payload
     *
     * @param int $status HTTP status code
     * @param string $description Error description
     * @param array $header HTTP header
     * @return Error Error payload
     */
    public function error($status, $description, array $header = [])
    {
        return Kernel::create(
            Error::class,
            [[
                'status' => $status,
                'description' => $description,
                'header' => $header
            ]]
        );
    }
}
