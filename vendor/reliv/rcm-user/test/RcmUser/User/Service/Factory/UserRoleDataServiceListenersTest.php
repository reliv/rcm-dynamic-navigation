<?php
/**
 * UserRoleDataServiceListenersTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Service\Factory;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Service\Factory\UserRoleDataServiceListeners;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

/**
 * Class UserRoleDataServiceListenersTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Service\Factory\UserRoleDataServiceListeners
 */
class UserRoleDataServiceListenersTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new UserRoleDataServiceListeners();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            '\RcmUser\User\Event\UserRoleDataServiceListeners',
            $service
        );
        //

        $this->assertInstanceOf(
            '\RcmUser\User\Service\UserRoleService',
            $service->getUserRoleService()
        );
    }
}
 