<?php
/**
 * ViewHelperRcmUserGetCurrentUserTest.php
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
use RcmUser\View\Service\Factory\ViewHelperRcmUserGetCurrentUser;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

/**
 * Class ViewHelperRcmUserGetCurrentUserTest
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
 * @covers    \RcmUser\View\Service\Factory\ViewHelperRcmUserGetCurrentUser
 */
class ViewHelperRcmUserGetCurrentUserTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new ViewHelperRcmUserGetCurrentUser();

        $service = $factory->createService($this->getMockHelperPluginManager());
        $this->assertInstanceOf(
            '\RcmUser\View\Helper\RcmUserGetCurrentUser',
            $service
        );
        //
    }
}
 