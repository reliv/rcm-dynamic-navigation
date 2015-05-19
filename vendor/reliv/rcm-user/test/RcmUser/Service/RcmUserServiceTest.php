<?php
/**
 * TestRcmUserService.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUserTest\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Service;

use RcmUser\Service\RcmUserService;
use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\User;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class RcmUserServiceTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\Service\RcmUserService
 */
class RcmUserServiceTest extends Zf2TestCase
{
    public $rcmUserService;
    public $userDataService;
    public $userPropertyService;
    public $userAuthService;
    public $authorizeService;


    public function setUp()
    {
        $this->buildRcmUserService();

        parent::setUp();
    }

    protected function getNewUser($prefix = 'A')
    {
        $user = new User();
        $user->setId($prefix . '_id');
        $user->setUsername($prefix . '_username');
        $user->setPassword($prefix . '_password');
        $user->setState($prefix . '_state');
        $user->setProperties(['property1', $prefix . '_property1']);
        $user->setProperty('property2', $prefix . '_property2');

        return $user;
    }

    public function getRcmUserService()
    {
        if (!isset($this->rcmUserService)) {

            $this->buildRcmUserService();
        }

        return $this->rcmUserService;
    }

    public function buildRcmUserService()
    {
        /*
        $config = new Config();
        */

        $user = $this->getNewUser();
        $userResult = new \RcmUser\User\Result($user);
        $authResult
            = new \Zend\Authentication\Result(
            \Zend\Authentication\Result::SUCCESS,
            $user
        );

        /*
        $userDataService = new UserDataService();
        $userPropertyService = new UserPropertyService();
        $userAuthService = new UserAuthenticationService();
        */

        $this->userDataService = $this->getMockBuilder(
            '\RcmUser\User\Service\UserDataService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->userDataService->expects($this->any())
            ->method('buildUser')
            ->will($this->returnValue($userResult));
        $this->userDataService->expects($this->any())
            ->method('readUser')
            ->will($this->returnValue($userResult));
        $this->userDataService->expects($this->any())
            ->method('createUser')
            ->will($this->returnValue($userResult));
        $this->userDataService->expects($this->any())
            ->method('updateUser')
            ->will($this->returnValue($userResult));
        $this->userDataService->expects($this->any())
            ->method('deleteUser')
            ->will($this->returnValue($userResult));

        /////
        $this->userPropertyService = $this->getMockBuilder(
            '\RcmUser\User\Service\UserPropertyService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->userPropertyService->expects($this->any())
            ->method('getUserProperty')
            ->will($this->returnValue(array('some', 'user', 'property')));

        /////
        $this->userAuthService = $this->getMockBuilder(
            '\RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->userAuthService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));
        $this->userAuthService->expects($this->any())
            ->method('validateCredentials')
            ->will($this->returnValue($authResult));
        $this->userAuthService->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($authResult));
        $this->userAuthService->expects($this->any())
            ->method('clearIdentity')
            ->will($this->returnValue(true));
        $this->userAuthService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));
        $this->userAuthService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));

        /////
        $this->authorizeService = $this->getMockBuilder(
            '\RcmUser\Acl\Service\AuthorizeService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->authorizeService->expects($this->any())
            ->method('isAllowed')
            ->will($this->returnValue(true));

        // @todo details:  hasRoleBasedAccess(User $user, $roleId)
        $this->authorizeService->expects($this->any())
            ->method('hasRoleBasedAccess')
            ->will($this->returnValue(true));


        $this->rcmUserService = new RcmUserService();
        $this->rcmUserService->setUserDataService($this->userDataService);
        $this->rcmUserService->setUserPropertyService(
            $this->userPropertyService
        );
        $this->rcmUserService->setUserAuthService($this->userAuthService);
        $this->rcmUserService->setAuthorizeService($this->authorizeService);

    }

    public function testSetGetUserDataService()
    {
        $rcmUserService = new RcmUserService();

        $rcmUserService->setUserDataService($this->userDataService);

        $service = $rcmUserService->getUserDataService();

        $this->assertInstanceOf(
            '\RcmUser\User\Service\UserDataService',
            $service,
            'Getter or setter failed.'
        );
    }

    public function testSetGetUserPropertyService()
    {
        $rcmUserService = new RcmUserService();

        $rcmUserService->setUserPropertyService($this->userPropertyService);

        $service = $rcmUserService->getUserPropertyService();

        $this->assertInstanceOf(
            '\RcmUser\User\Service\UserPropertyService',
            $service,
            'Getter or setter failed.'
        );
    }

    public function testSetGetUserAuthService()
    {
        $rcmUserService = new RcmUserService();

        $rcmUserService->setUserAuthService($this->userAuthService);

        $service = $rcmUserService->getUserAuthService();

        $this->assertInstanceOf(
            '\RcmUser\Authentication\Service\UserAuthenticationService',
            $service,
            'Getter or setter failed.'
        );
    }

    public function testSetGetAuthorizeService()
    {
        $rcmUserService = new RcmUserService();

        $rcmUserService->setAuthorizeService($this->authorizeService);

        $service = $rcmUserService->getAuthorizeService();

        $this->assertInstanceOf(
            '\RcmUser\Acl\Service\AuthorizeService',
            $service,
            'Getter or setter failed.'
        );
    }

    public function testGetUser()
    {

        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->getUser($user);

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );

        $result = $this->getRcmUserService()->getUserById($user->getId());

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );

        $result = $this->getRcmUserService()->getUserByUsername(
            $user->getUsername()
        );

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );

        // test no not found
    }

    public function testUserExists()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->userExists($user);

        $this->assertTrue($result, 'User was not found to exist.');

        // test for not found
    }

    public function testIsIdentity()
    {
        $user = $this->getNewUser('A');
        $user->setId(null);

        $result = $this->getRcmUserService()->isIdentity($user);

        $this->assertTrue($result, 'User did not match by username.');

        $user->setId('A_id');

        $result = $this->getRcmUserService()->isIdentity($user);

        $this->assertTrue($result, 'User did not match by id.');

        $user2 = new User();

        $result = $this->getRcmUserService()->isIdentity($user2);

        $this->assertFalse($result, 'User matched but should not have.');
    }

    public function testReadUser()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->readUser($user);

        $this->assertInstanceOf(
            '\RcmUser\User\Result',
            $result,
            'Did not return instance of Result.'
        );
    }

    public function testCreateUser()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->createUser($user);

        $this->assertInstanceOf(
            '\RcmUser\User\Result',
            $result,
            'Did not return instance of Result.'
        );
    }

    public function testUpdateUser()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->updateUser($user);

        $this->assertInstanceOf(
            '\RcmUser\User\Result',
            $result,
            'Did not return instance of Result.'
        );
    }

    public function testDeleteUser()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->deleteUser($user);

        $this->assertInstanceOf(
            '\RcmUser\User\Result',
            $result,
            'Did not return instance of Result.'
        );
    }


    public function testGetUserProperty()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->getUserProperty(
            $user,
            'somePropertName'
        );

        $this->assertTrue(is_array($result), 'Did not return our test array.');

        $this->assertContains(
            'some',
            $result,
            'Did not return our test array value.'
        );
    }

    public function testGetCurrentUserProperty()
    {

        $result = $this->getRcmUserService()->getCurrentUserProperty(
            'somePropertName'
        );

        $this->assertTrue(is_array($result), 'Did not return our test array.');

        $this->assertContains(
            'some',
            $result,
            'Did not return our test array value.'
        );
    }

    public function testValidateCredentials()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->validateCredentials($user);

        $this->assertInstanceOf(
            '\Zend\Authentication\Result',
            $result,
            'Did not return instance of Result.'
        );
    }

    public function testAuthenticate()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->authenticate($user);

        $this->assertInstanceOf(
            '\Zend\Authentication\Result',
            $result,
            'Did not return instance of Result.'
        );
    }

    public function testClearIdentity()
    {
        $result = $this->getRcmUserService()->clearIdentity('somePropertName');

        $this->assertTrue($result, 'Did not return true.');
    }

    public function testGetIdentity()
    {
        $result = $this->getRcmUserService()->getIdentity();

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );

        $result = $this->getRcmUserService()->getCurrentUser();

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );

        $hasIdentity = $this->getRcmUserService()->hasIdentity();

        $this->assertTrue($hasIdentity, 'Did not return true.');

        $this->getRcmUserService()->refreshIdentity();

        $result = $this->getRcmUserService()->getIdentity();

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );

        $this->getRcmUserService()->setIdentity($result);

        $userException = null;

        try {
            $newUser = new User();

            $newUser->setId('newguy');

            $this->getRcmUserService()->setIdentity($newUser);

        } catch (\Exception $e) {

            $userException = $e;

        }

        $this->assertInstanceOf('\Exception', $userException);
    }

    public function testIsAllowed()
    {
        $result = $this->getRcmUserService()->isAllowed('someResource');

        $this->assertTrue($result, 'Did not return true.');
    }


    public function testHasRoleBasedAccess()
    {
        $user = $this->getRcmUserService()->getIdentity();

        $result = $this->getRcmUserService()->hasRoleBasedAccess('someRole');

        $this->assertTrue($result, 'Did not return true.');

        $result = $this->getRcmUserService()->hasUserRoleBasedAccess($user, 'someRole');

        $this->assertTrue($result, 'Did not return true.');
    }

    public function testBuildNewUser()
    {
        $result = $this->getRcmUserService()->buildNewUser();

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );
    }

    public function testBuildUser()
    {
        $user = $this->getNewUser();

        $result = $this->getRcmUserService()->buildUser($user);

        $this->assertInstanceOf(
            '\RcmUser\User\Entity\User',
            $result,
            'Did not return instance of Result.'
        );
    }


}