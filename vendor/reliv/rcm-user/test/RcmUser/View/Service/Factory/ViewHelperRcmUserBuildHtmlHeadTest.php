<?php
/**
 * ViewHelperRcmUserBuildHtmlHeadTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\View\Service\Factory;

use RcmUser\Test\Zf2TestCase;
use RcmUser\View\Service\Factory\ViewHelperRcmUserBuildHtmlHead;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

/**
 * Class ViewHelperRcmUserBuildHtmlHeadTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\View\Service\Factory\ViewHelperRcmUserBuildHtmlHead
 */
class ViewHelperRcmUserBuildHtmlHeadTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new ViewHelperRcmUserBuildHtmlHead();

        $service = $factory->createService($this->getMockHelperPluginManager());
        $this->assertInstanceOf(
            '\RcmUser\View\Helper\RcmUserBuildHtmlHead',
            $service
        );
        //
    }
}
 