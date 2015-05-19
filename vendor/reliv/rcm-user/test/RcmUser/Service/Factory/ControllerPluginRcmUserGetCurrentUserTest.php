<?php
/**
 * ControllerPluginRcmUserGetCurrentUserTest.php
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

use RcmUser\Service\Factory\ControllerPluginRcmUserGetCurrentUser;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class ControllerPluginRcmUserGetCurrentUserTest
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
 * @covers    \RcmUser\Service\Factory\ControllerPluginRcmUserGetCurrentUser
 */
class ControllerPluginRcmUserGetCurrentUserTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new ControllerPluginRcmUserGetCurrentUser();

        $service = $factory->createService($this->getMockControllerManager());
        $this->assertInstanceOf(
            '\RcmUser\Controller\Plugin\RcmUserGetCurrentUser',
            $service

        );
        //
    }
}
 