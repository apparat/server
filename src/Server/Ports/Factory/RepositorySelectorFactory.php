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

namespace Apparat\Server\Ports\Factory;

use Apparat\Server\Ports\Types\Selector;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Repository selector factory
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class RepositorySelectorFactory
{
    /**
     * List of relevant date parts
     *
     * @var array
     */
    protected static $dateParts = null;

    /**
     * Create a repository selector from a server request
     *
     * @param ServerRequestInterface $serverRequest Server request
     * @return string Repository selector
     */
    public static function createObjectSelectorFromRequest(ServerRequestInterface $serverRequest)
    {
        $selector = [''];

        // Collect the relevant date parts
        foreach (self::getDateParts() as $datePart) {
            $selector[] = $serverRequest->getAttribute($datePart);
        }

        // Compose the hidden / ID / type part
        $hiddenIdType = $serverRequest->getAttribute(Selector::HIDDEN) ? '.' : '';
        $hiddenIdType .= $serverRequest->getAttribute(Selector::ID).'-';
        $hiddenIdType .= $serverRequest->getAttribute(Selector::TYPE);
        $selector[] = $hiddenIdType;

        // Compose the draft / revision / format part
        $draftRevFormat = $serverRequest->getAttribute(Selector::DRAFT) ? '.' : '';
        $draftRevFormat .= $serverRequest->getAttribute(Selector::ID).'-';
        $draftRevFormat .= $serverRequest->getAttribute(Selector::REVISION);
        $selector[] = $draftRevFormat;

        return implode('/', $selector);
    }

    /**
     * Create and return the list of relevant date parts
     *
     * @return array Date parts
     */
    protected static function getDateParts()
    {
        if (self::$dateParts === null) {
            self::$dateParts = array_slice(
                [
                    Selector::YEAR,
                    Selector::MONTH,
                    Selector::DAY,
                    Selector::HOUR,
                    Selector::MINUTE,
                    Selector::SECOND,
                ],
                0,
                intval(getenv('OBJECT_DATE_PRECISION'))
            );
        }
        return self::$dateParts;
    }
}
