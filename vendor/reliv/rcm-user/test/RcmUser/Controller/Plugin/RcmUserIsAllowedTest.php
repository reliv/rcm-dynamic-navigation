<?php
/**
 * RcmUserIsAllowedTest.php
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

use RcmUser\Controller\Plugin\RcmUserIsAllowed;
use RcmUser\User\Entity\User;

/**
 * Class RcmUserIsAllowedTest
 *
 * RcmUserIsAllowedTest
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
 * @covers    \RcmUser\Controller\Plugin\RcmUserIsAllowed
 */
class RcmUserIsAllowedTest extends \PHPUnit_Framework_TestCase
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
            ->method('isAllowed')
            ->will($this->returnValue(true));

        $rcmUserIsAllowed = new RcmUserIsAllowed(
            $this->userAuth,
            $this->userAuthenticationService
        );

        $return = $rcmUserIsAllowed->__invoke('someresid');

        $this->assertTrue($return);

    }
}
 