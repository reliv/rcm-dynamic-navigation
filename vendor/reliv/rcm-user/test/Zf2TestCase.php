<?php
/**
 * Zf2TestCase.php
 *
 * Zf2TestCase
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmUser\Test;

use RcmUser\Config\Config;

require_once dirname(__FILE__) . '/Bootstrap.php';

/**
 * Class Zf2TestCase
 *
 * Zf2TestCase
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Zf2TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface $mockServiceLocator
     */
    public $mockServiceLocator;

    /**
     * @var array $mockServices
     */
    public $mockServices;

    /**
     * @var \Zend\Mvc\Controller\ControllerManager $mockControllerManager
     */
    public $mockControllerManager;

    /**
     * @var \Zend\View\HelperPluginManager $mockHelperPluginManager
     */
    public $mockHelperPluginManager;

    /**
     * @var array $valueMap
     */
    public $valueMap;

    /**
     * @var array $mockRcmUserConfig
     */
    public $mockRcmUserConfig
        = [
            'htmlAssets' => [
                'js' => [
                    '/test.js',
                ],

                'css' => [
                    '/test.css',
                ],
            ],
            'User\Config' => [

                'ValidUserStates' => [
                    'disabled', // **REQUIRED for User entity**
                    'enabled',
                ],
                'DefaultUserState' => 'enabled',
                'Encryptor.passwordCost' => 14,
                'InputFilter' => [

                    'username' => [
                        'name' => 'username',
                        'required' => true,
                        'filters' => [
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 100,
                                ],
                            ],
                        ],
                    ],
                    'password' => [
                        'name' => 'password',
                        'required' => true,
                        'filters' => [],
                        'validators' => [
                            [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 6,
                                    'max' => 100,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Auth\Config' => [
                'ObfuscatePasswordOnAuth' => true,
            ],
            'Acl\Config' => [

                'DefaultGuestRoleIds' => ['guest'],
                'DefaultUserRoleIds' => ['user'],
                'SuperAdminRoleId' => 'admin',
                'GuestRoleId' => 'guest',
                'ResourceProviders' => [

                    'RcmUser' => 'RcmUser\Provider\RcmUserAclResourceProvider',
                    'RcmUser.TEST' => [
                        'TESTONE' => [
                            'resourceId' => 'TESTONE',
                            'parentResourceId' => null,
                            'privileges' => [
                                'read',
                                'update',
                                'create',
                                'delete',
                                'execute',
                            ],
                            'name' => 'Test resource one.',
                            'description' => 'test resource one desc.',
                        ],
                        'TESTTWO' => [
                            'resourceId' => 'TESTTWO',
                            'parentResourceId' => 'TESTONE',
                            'privileges' => [
                                'read',
                                'update',
                                'create',
                                'delete',
                                'execute',
                            ],
                            'name' => 'Test resource two.',
                            'description' => 'test resource two desc.',
                        ]
                    ],
                ],
            ],
        ];

    /**
     * getMockServices
     *
     * @return array
     */
    public function getMockServices()
    {
        if (isset($this->mockServices)) {

            return $this->mockServices;
        }

        $this->mockServices = [

            'RcmUser\Config' => $this->mockRcmUserConfig,
            'RcmUser\User\Config' =>
                new Config($this->mockRcmUserConfig['User\Config']),
            'RcmUser\Auth\Config' =>
                new Config($this->mockRcmUserConfig['Auth\Config']),
            'RcmUser\Acl\Config' =>
                new Config($this->mockRcmUserConfig['Acl\Config']),
            'RcmUser\User\Service\UserDataService' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Service\UserDataService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\Service\UserPropertyService' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Service\UserPropertyService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\Service\UserRoleService' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Service\UserRoleService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\UserDataMapper' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Db\UserDataMapperInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\UserRolesDataMapper' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Db\UserRolesDataMapperInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\Data\UserValidator' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Data\UserValidatorInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\Encryptor' =>
                $this->getMockBuilder(
                    '\Zend\Crypt\Password\PasswordInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\Data\UserDataPreparer' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Data\UserDataPreparerInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\UserDataServiceListeners' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Event\AbstractUserDataServiceListeners'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\User\UserRoleDataServiceListeners' =>
                $this->getMockBuilder(
                    '\RcmUser\User\Event\AbstractUserDataServiceListeners'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Authentication\Service\UserAuthenticationService' =>
                $this->getMockBuilder(
                    '\RcmUser\Authentication\Service\UserAuthenticationService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Authentication\Adapter' =>
                $this->getMockBuilder(
                    '\RcmUser\Authentication\Adapter\UserAdapter'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Authentication\Storage' =>
                $this->getMockBuilder(
                    '\RcmUser\Authentication\Storage\UserSession'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Authentication\AuthenticationService' =>
                $this->getMockBuilder(
                    '\RcmUser\Authentication\Service\AuthenticationService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Authentication\UserAuthenticationServiceListeners' =>
                $this->getMockBuilder(
                    '\RcmUser\Authentication\Event\UserAuthenticationServiceListeners'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Acl\Service\AclResourceService' =>
                $this->getMockBuilder(
                    '\RcmUser\Acl\Service\AclResourceService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Acl\Service\AuthorizeService' =>
                $this->getMockBuilder(
                    '\RcmUser\Acl\Service\AuthorizeService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Acl\AclRoleDataMapper' =>
                $this->getMockBuilder(
                    '\RcmUser\Acl\Db\AclRoleDataMapperInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Acl\AclRuleDataMapper' =>
                $this->getMockBuilder(
                    '\RcmUser\Acl\Db\AclRuleDataMapperInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Acl\AclDataService' =>
                $this->getMockBuilder(
                    '\RcmUser\Acl\Service\AclDataService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Service\RcmUserService' =>
                $this->getMockBuilder(
                    '\RcmUser\Service\RcmUserService'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Provider\RcmUserAclResourceProvider' =>
                $this->getMockBuilder(
                    '\RcmUser\Provider\RcmUserAclResourceProvider'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            'RcmUser\Event\Listeners' =>
                [],
            'RcmUser\Log\Logger' =>
                $this->getMockBuilder(
                    '\Zend\Log\LoggerInterface'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            /////////////////////////////////////////////////////////
            'Doctrine\ORM\EntityManager' =>
                $this->getMockBuilder(
                    '\Doctrine\ORM\EntityManager'
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
        ];

        return $this->mockServices;
    }

    /**
     * getValueMap
     *
     * @return array
     */
    public function getValueMap()
    {
        if (isset($this->valueMap)) {

            return $this->valueMap;
        }
        $mockServices = $this->getMockServices();
        $this->valueMap = [];
        foreach ($mockServices as $key => $value) {

            $this->valueMap[] = [$key, $value];
        }

        return $this->valueMap;

    }

    /**
     * getMockServiceLocator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getMockServiceLocator()
    {
        if (isset($this->mockServiceLocator)) {

            return $this->mockServiceLocator;
        }

        $this->mockServiceLocator = $this->getMockBuilder(
            '\Zend\ServiceManager\ServiceLocatorInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockServiceLocator->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($this->getValueMap()));

        return $this->mockServiceLocator;
    }

    /**
     * getMockControllerManager
     *
     * @return \Zend\Mvc\Controller\ControllerManager
     */
    public function getMockControllerManager()
    {
        if (isset($this->mockControllerManager)) {

            return $this->mockControllerManager;
        }

        $this->mockControllerManager = $this->getMockBuilder(
            '\Zend\Mvc\Controller\ControllerManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockControllerManager->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($this->getMockServiceLocator()));

        return $this->mockControllerManager;
    }

    /**
     * getMockViewManager
     *
     * @return \Zend\View\HelperPluginManager
     */
    public function getMockHelperPluginManager()
    {
        if (isset($this->mockHelperPluginManager)) {

            return $this->mockHelperPluginManager;
        }

        $this->mockHelperPluginManager = $this->getMockBuilder(
            '\Zend\View\HelperPluginManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHelperPluginManager->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($this->getMockServiceLocator()));

        return $this->mockHelperPluginManager;
    }
}