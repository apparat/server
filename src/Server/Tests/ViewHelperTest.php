<?php

/**
 * apparat-server
 *
 * @category    Apparat
 * @package     Apparat\Server
 * @subpackage  Apparat\Server\Tests
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

namespace Apparat\Server\Tests;

use Apparat\Dev\Tests\AbstractTest;
use Apparat\Server\Module;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * View helper tests
 *
 * @package Apparat\Server
 * @subpackage Apparat\Server\Tests
 */
class ViewHelperTest extends AbstractTest
{
    /**
     * Test the Datetime view helper
     */
    public function testDatetimeViewHelper()
    {
        $now = new \DateTimeImmutable('now');
        $view = new TemplateView();
        $view->getViewHelperResolver()->addNamespace('as', 'Apparat\\Server\\Ports\\ViewHelpers');
        $view->getTemplatePaths()->setTemplatePathAndFilename(
            __DIR__.DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'non-repo'.DIRECTORY_SEPARATOR.'Singles'.
            DIRECTORY_SEPARATOR.'Datetime.html'
        );
        $view->assign('datetime', $now);
        $this->assertEquals($now->format('c'), trim($view->render()));
    }

    /**
     * Test the CommonMark HTML view helper
     */
    public function testCommonMarkHtmlViewHelper()
    {
        $view = new TemplateView();
        $view->getViewHelperResolver()->addNamespace('as', 'Apparat\\Server\\Ports\\ViewHelpers');
        $view->getTemplatePaths()->setTemplatePathAndFilename(
            __DIR__.DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'non-repo'.DIRECTORY_SEPARATOR.'Singles'.
            DIRECTORY_SEPARATOR.'CommonMark.html'
        );
        $view->assign('commonMark', '# Headline');
        $this->assertEquals('<h1>Headline</h1>', trim($view->render()));
    }

    /**
     * Test an invalid Fluid cache directory
     *
     * @expectedException \RuntimeException
     * @expectedExceptionCode 1470500567
     */
    public function testInvalidCacheDirectory() {
        Module::validateFluidCacheDirectory(md5(rand()));
    }
}
