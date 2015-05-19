<?php
/**
 * RcmUserServiceTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Service\Factory;

use RcmUser\Service\Factory\RcmUserService;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class RcmUserServiceTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Service\Factory\RcmUserService
 */
class RcmUserServiceTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new RcmUserService();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            '\RcmUser\Service\RcmUserService',
            $service
        );
        //
        //
        $this->assertInstanceOf(
            '\RcmUser\Authentication\Service\UserAuthenticationService',
            $service->getUserAuthService()
        );

        $this->assertInstanceOf(
            '\RcmUser\User\Service\UserDataService',
            $service->getUserDataService()
        );

        $this->assertInstanceOf(
            '\RcmUser\User\Service\UserPropertyService',
            $service->getUserPropertyService()
        );

        $this->assertInstanceOf(
            '\RcmUser\Acl\Service\AuthorizeService',
            $service->getAuthorizeService()
        );
    }
}
 