<?php
/**
 * RcmUserGetCurrentUserTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Controller\Plugin;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\Controller\Plugin\RcmUserGetCurrentUser;
use RcmUser\User\Entity\User;

/**
 * Class RcmUserGetCurrentUserTest
 *
 * RcmUserGetCurrentUserTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Controller\Plugin\RcmUserGetCurrentUser
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
 