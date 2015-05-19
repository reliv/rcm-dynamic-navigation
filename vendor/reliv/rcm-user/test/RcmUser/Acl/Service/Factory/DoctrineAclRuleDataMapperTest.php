<?php
/**
 * DoctrineAclRuleDataMapperTest.php
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

use RcmUser\Acl\Service\Factory\DoctrineAclRuleDataMapper;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

/**
 * Class DoctrineAclRuleDataMapperTest
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
 * @covers    \RcmUser\Acl\Service\Factory\DoctrineAclRuleDataMapper
 */
class DoctrineAclRuleDataMapperTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new DoctrineAclRuleDataMapper();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            'RcmUser\Acl\Db\DoctrineAclRuleDataMapper',
            $service
        );

        //
        $this->assertInstanceOf(
            'Doctrine\ORM\EntityManager',
            $service->getEntityManager()
        );

        $this->assertTrue(
            is_string($service->getEntityClass())
        );
    }
}
 