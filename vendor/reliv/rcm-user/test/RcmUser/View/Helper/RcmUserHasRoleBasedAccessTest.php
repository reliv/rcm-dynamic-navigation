<?php

namespace RcmUser\Test\View\Helper;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\User\Entity\User;
use RcmUser\View\Helper\RcmUserHasRoleBasedAccess;

/**
 * Class RcmUserHasRoleBasedAccessTest
 *
 * RcmUserHasRoleBasedAccessTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\View\Helper\RcmUserIsAllowed
 */
class RcmUserHasRoleBasedAccessTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $user = new User('123');

        $this->userAuthenticationService = $this->getMockBuilder(
            '\RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->userAuthenticationService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));

        $this->userAuth = $this->getMockBuilder(
            '\RcmUser\Acl\Service\AuthorizeService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->userAuth->expects($this->any())
            ->method('hasRoleBasedAccess')
            ->will($this->returnValue(true));

        $rcmUserHasRoleBasedAcces = new RcmUserHasRoleBasedAccess(
            $this->userAuth,
            $this->userAuthenticationService
        );

        $return = $rcmUserHasRoleBasedAcces->__invoke('someroleid');

        $this->assertTrue($return);

    }
}
 