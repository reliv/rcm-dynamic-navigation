<?php
/**
 * AclDataServiceTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Acl\Service\Factory;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

use RcmUser\Acl\Service\Factory\AclDataService;
use RcmUser\Test\Zf2TestCase;

/**
 * Class AclDataServiceTest
 *
 * AclDataServiceTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Service\Factory\AclDataService
 */
class AclDataServiceTest extends Zf2TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new AclDataService();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            'RcmUser\Acl\Service\AclDataService',
            $service
        );

        //
        $this->assertInstanceOf(
            'RcmUser\Acl\Db\AclRoleDataMapperInterface',
            $service->getAclRoleDataMapper()
        );

        $this->assertInstanceOf(
            'RcmUser\Acl\Db\AclRuleDataMapperInterface',
            $service->getAclRuleDataMapper()
        );

    }
}
 