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

namespace Apparat\Server\Ports\View;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * TYPO3 Fluid view
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Ports
 */
class TYPO3FluidView extends TemplateView implements ViewInterface
{
    /**
     * Constructor
     *
     * @param null|RenderingContextInterface $context
     */
    public function __construct(RenderingContextInterface $context = null)
    {
        // TODO Why gets this called twice?
//        var_dump($context);
        parent::__construct($context);
        $this->setTemplatePaths();
    }

    /**
     * Set the action name
     *
     * @param string $action Action name
     * @return ViewInterface Self reference
     */
    public function setAction($action)
    {
        $this->getRenderingContext()->setControllerAction($action);
        return $this;
    }

    /**
     * Set the template paths
     *
     * @todo Enable externally configured paths
     */
    protected function setTemplatePaths()
    {
        $paths = $this->getTemplatePaths();
        $paths->setLayoutRootPaths([__DIR__.DIRECTORY_SEPARATOR.'TYPO3Fluid'.DIRECTORY_SEPARATOR.'Layouts']);
        $paths->setTemplateRootPaths([__DIR__.DIRECTORY_SEPARATOR.'TYPO3Fluid'.DIRECTORY_SEPARATOR.'Templates']);
        $paths->setPartialRootPaths([__DIR__.DIRECTORY_SEPARATOR.'TYPO3Fluid'.DIRECTORY_SEPARATOR.'Partials']);
    }
}