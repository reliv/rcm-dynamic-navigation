<?php
/**
 * RcmUserGetCurrentUserTest.php
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

use RcmUser\User\Entity\User;
use RcmUser\View\Helper\RcmUserGetCurrentUser;

/**
 * Class RcmUserGetCurrentUserTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\View\Helper\RcmUserGetCurrentUser
 */
class RcmUserGetCurrentUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $user = new User('123');

        $this->rcmUserService = $this->getMockBuilder(
            'RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->rcmUserService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));

        $rcmUserGetCurrentUser
            = new RcmUserGetCurrentUser($this->rcmUserService);

        $identity = $rcmUserGetCurrentUser->__invoke();

        $this->assertEquals($user, $identity);

    }
}
 