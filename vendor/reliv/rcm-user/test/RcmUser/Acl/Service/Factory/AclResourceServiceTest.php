<?php
/**
 * AclResourceServiceTest.php
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

use RcmUser\Acl\Service\Factory\AclResourceService;
use RcmUser\Test\Zf2TestCase;

/**
 * Class AclResourceServiceTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Service\Factory\AclResourceService
 */
class AclResourceServiceTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new AclResourceService();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            'RcmUser\Acl\Service\AclResourceService',
            $service
        );

        //
        $this->assertTrue(
            is_array($service->getResourceProviders())
        );

        $this->assertInstanceOf(
            '\Zend\ServiceManager\ServiceLocatorInterface',
            $service->getServiceLocator()
        );

        $this->assertInstanceOf(
            'RcmUser\Acl\Entity\AclResource',
            $service->getRootResource()
        );

    }
}
 