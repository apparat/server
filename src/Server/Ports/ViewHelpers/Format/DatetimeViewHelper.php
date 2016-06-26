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

namespace Apparat\Server\Ports\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Datetime view helper
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class DatetimeViewHelper extends AbstractViewHelper
{
    /**
     * Don't escape the child elements
     *
     * @var boolean
     */
    protected $escapeChildren = FALSE;

    /**
     * Don't apply HTML special chars to the output
     *
     * @var boolean
     */
    protected $escapeOutput = FALSE;

    /**
     * Initialize the arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'value',
            \DateTimeInterface::class,
            'Date/time object',
            false,
            new \DateTimeImmutable('now')
        );
        $this->registerArgument('format', 'string', 'Date format', false, 'c');
    }

    /**
     * Returns a formatted representation of a date/time object
     *
     * @return string Date/time string representation
     */
    public function render()
    {
        /** @var \DateTimeInterface $value */
        $value = $this->arguments['value'];
        $format = $this->arguments['format'];
        return $value->format($format);
    }
}
