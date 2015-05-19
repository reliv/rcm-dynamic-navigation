<?php
/**
 * AuthorizeServiceTest.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Acl\Service;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Result;
use RcmUser\Test\Zf2TestCase;
use Zend\Di\ServiceLocator;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AuthorizeServiceTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\Acl\Service\AuthorizeService
 */
class AuthorizeServiceTest extends Zf2TestCase
{

    public $authorizeService;

    public function getAuthorizeService()
    {
        if (!isset($this->authorizeService)) {

            $this->buildAuthorizeService();
        }

        return $this->authorizeService;
    }

    public function buildAuthorizeService()
    {

        $aclResourceService = $this->getMockBuilder(
            'RcmUser\Acl\Service\AclResourceService'
        )
            ->disableOriginalConstructor()
            ->getMock();


        $aclDataService = $this->getMockBuilder(
            'RcmUser\Acl\Service\AclDataService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $aclDataService->expects($this->any())
            ->method('getNamespacedRoles')
            ->will(
                $this->returnValue(
                    new Result(
                        ['data' => 'here'],
                        Result::CODE_SUCCESS,
                        'Message'
                    )
                )
            );

        $aclDataService->expects($this->any())
            ->method('getSuperAdminRoleId')
            ->will($this->returnValue('admin'));

        $aclDataService->expects($this->any())
            ->method('getAllRules')
            ->will($this->returnValue([]));

        $aclDataService->expects($this->any())
            ->method('getRulesByResource')
            ->will($this->returnValue([]));

        $this->authorizeService = new AuthorizeService(
            $aclResourceService,
            $aclDataService
        );
    }

    public function testGetSet()
    {

        $this->buildAuthorizeService();

        /** @var AuthorizeService $authServ */
        $authServ = $this->getAuthorizeService();

        $this->assertInstanceOf(
            '\RcmUser\Acl\Service\AclResourceService',
            $authServ->getAclResourceService()
        );
        $this->assertInstanceOf(
            '\RcmUser\Acl\Service\AclDataService',
            $authServ->getAclDataService()
        );

    }

    public function testGetRoles()
    {
    }

    public function testIsAllowed()
    {
        /* @todo Fix Me
         * $resource = "some.resource";
         *
         * $result = $this->getAuthorizeService()->isAllowed($resource);
         *
         * $this->assertTrue($result, 'True not returned.');
         * */
    }

    public function testParseResource()
    {
        $resource = "some.resource";

        $result = $this->getAuthorizeService()->parseResource($resource);

        $this->assertContains(
            'some',
            $result,
            'Resource string not parsed correctly'
        );
    }
}
 