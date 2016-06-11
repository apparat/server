<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Domain\Service
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

use Apparat\Server\Application\Factory\PayloadFactory;
use Apparat\Server\Domain\Payload\PayloadInterface;

/**
 * Abstract list service
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Domain
 */
class AbstractListService extends AbstractService
{
    /**
     * Payload factory
     *
     * @var PayloadFactory
     */
    protected $payloadFactory;

    /**
     * Find objects by parameters
     *
     * @param int|string $year Year
     * @param int|string $month Month
     * @param int|string $day Day
     * @param int|string $hour Hour
     * @param int|string $minute Minute
     * @param int|string $second Second
     * @param boolean $hidden Find hidden objects
     * @param string $type Object type
     * @param boolean $draft Find object drafts
     * @param int|null $revision Object revision
     * @return PayloadInterface Payload
     */
    public function findObjects($year, $month, $day, $hour, $minute, $second, $hidden, $type, $draft, $revision)
    {
        return $this->payloadFactory->found([]);
    }
}
