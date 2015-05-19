<?php
/**
 * UserAuthenticationServiceTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Authentication\Service\Factory;

use RcmUser\Authentication\Service\Factory\UserAuthenticationService;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

/**
 * Class UserAuthenticationServiceTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Authentication\Service\Factory\UserAuthenticationService
 */
class UserAuthenticationServiceTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new UserAuthenticationService();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            'RcmUser\Authentication\Service\UserAuthenticationService',
            $service
        );

        //
        $this->assertEquals(
            $this->mockRcmUserConfig['Auth\Config']['ObfuscatePasswordOnAuth'],
            $service->getObfuscatePassword()
        );

    }
}
 