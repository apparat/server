<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Infrastructure
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

namespace Apparat\Server\Infrastructure\Route;

use Apparat\Server\Ports\Types\Selector;

/**
 * Aura default route
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Infrastructure
 */
class AuraDefaultRoute extends AuraRoute
{
    /**
     * Pre-process the route attributes
     */
    public function preprocessAttributes()
    {
        parent::preprocessAttributes();

        // Hidden objects
        $this->attributes[Selector::HIDDEN] = !empty($this->attributes[Selector::HIDDEN]);

        // Object ID
        $this->attributes[Selector::ID] =
            ((empty($this->attributes[Selector::ID]) || ($this->attributes[Selector::ID] == Selector::WILDCARD)) ?
                Selector::WILDCARD : intval($this->attributes[Selector::ID]));

        // Object type
        $this->attributes[Selector::TYPE] = empty($this->attributes['dashtype']) ?
            Selector::WILDCARD : ltrim($this->attributes['dashtype'], '-');
        unset($this->attributes['dashtype']);

        // Draft objects
        $this->attributes[Selector::DRAFT] =
            !empty($this->attributes['draftid']) && strpos($this->attributes['draftid'], '.');
        unset($this->attributes['draftid']);

        // Object revisions
        $this->attributes[Selector::REVISION] =
            (empty($this->attributes['dashrevision']) || $this->attributes[Selector::DRAFT]) ?
                Selector::WILDCARD : ltrim($this->attributes[Selector::REVISION], '-');
        if ($this->attributes[Selector::REVISION] !== Selector::WILDCARD) {
            $this->attributes[Selector::REVISION] = intval($this->attributes[Selector::REVISION]);
        }
        unset($this->attributes['dashrevision']);

        // Object resource format
        $this->attributes[Selector::FORMAT] = empty($this->attributes[Selector::FORMAT]) ?
            Selector::WILDCARD : ltrim($this->attributes[Selector::FORMAT], '.');
    }
}
