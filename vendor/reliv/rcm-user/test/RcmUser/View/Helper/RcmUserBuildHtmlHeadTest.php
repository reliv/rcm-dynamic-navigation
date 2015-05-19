<?php
/**
 * RcmUserBuildHtmlHeadTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\View\Helper;

require_once __DIR__ . '/../../../Zf2TestCase.php';
require_once __DIR__ . '/MockView.php';

use
    RcmUser\View\Helper\RcmUserBuildHtmlHead;

/**
 * Class RcmUserBuildHtmlHeadTest
 *
 * RcmUserBuildHtmlHeadTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\View\Helper\RcmUserBuildHtmlHead
 */
class RcmUserBuildHtmlHeadTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \RcmUser\View\Helper\RcmUserBuildHtmlHead $rcmUserBuildHtmlHead
     */
    public $rcmUserBuildHtmlHead;

    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    public $view;

    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $headLink = $this->getMockBuilder(
            'Zend\View\Helper\HeadLink'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $headLink->expects($this->any())
            ->method('appendStylesheet')
            ->will($this->returnValue(true));

        $headScript = $this->getMockBuilder(
            'Zend\View\Helper\HeadScript'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $headScript->expects($this->any())
            ->method('appendFile')
            ->will($this->returnValue(true));

        $this->view = $this->getMockBuilder(
            'RcmUser\Test\View\Helper\MockView'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->view->expects($this->any())
            ->method('headLink')
            ->will($this->returnValue($headLink));

        $this->view->expects($this->any())
            ->method('headScript')
            ->will($this->returnValue($headScript));

        $htmlAssets = [
            'js' => [
                '/test.js',
            ],

            'css' => [
                '/test.css',
            ],
        ];

        $this->rcmUserBuildHtmlHead = new RcmUserBuildHtmlHead($htmlAssets);

        $this->rcmUserBuildHtmlHead->setView($this->view);

        $this->rcmUserBuildHtmlHead->getView();

        $this->rcmUserBuildHtmlHead->__invoke();
    }


}