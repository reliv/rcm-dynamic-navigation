<?php
/**
 * module.config.php
 *
 * Module configuration
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
return array(

    'RcmUser' => array(
        /**
         * Include any paths for JavaScript and CSS here
         * The included views require:
         * - AngularJS
         * - Angular-UI
         * - TwitterBootstrap
         */
        'htmlAssets' => array(
            'js' => array(
                '/modules/rcm-angular-js/angular/angular.js',
                '/modules/rcm-twitter-bootstrap/bootstrap/js/bootstrap.js',
                '/modules/rcm-angular-js/angular-ui/bootstrap/ui-bootstrap-tpls-0.11.0.min.js',
            ),

            'css' => array(
                '/modules/rcm-twitter-bootstrap/bootstrap/css/bootstrap.css',
            ),
        ),
        'User\Config' => array(

            /*
             * ValidUserStates
             * Used for UI
             */
            'ValidUserStates' => array(
                'disabled',
                // **REQUIRED for User entity**
                'enabled',
            ),
            /*
             * DefaultUserState
             * Used in:
             *  RcmUser\User\Service\UserDataService
             *
             * This is the default user state that will
             * be set on create/update if none is set.
             */
            'DefaultUserState' => 'enabled',
            /*
             * Encryptor.passwordCost
             * Used in:
             *  RcmUser\User\Encryptor
             *
             * This should only be changed if you know what you are doing.
             */
            'Encryptor.passwordCost' => 14,
            /*
             * InputFilter
             * Used in:
             *  RcmUser\User\Db\UserDataMapper
             *
             * This input filter will be applied
             * to the User object on create and save.
             */
            'InputFilter' => array(

                'username' => array(
                    'name' => 'username',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 3,
                                'max' => 100,
                            ),
                        ),
                        // Help protect from XSS
                        array(
                            'name' => 'Regex',
                            'options' => array(
                                'pattern' => "/^[a-zA-Z0-9-_@'.]+$/",
                                //'pattern' => "/[<>]/",
                                'messageTemplates' => array(
                                    \Zend\Validator\Regex::NOT_MATCH => "Username can only contain letters, numbers and charactors: . - _ @ '."
                                )
                            ),
                        ),
                    ),
                ),
                'password' => array(
                    'name' => 'password',
                    'required' => true,
                    'filters' => array(),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 6,
                                'max' => 100,
                            ),
                        ),
                        /*
                        array(
                            'name' => 'Regex',
                            'options' => array(
                                'pattern' => '^(?=.*\d)(?=.*[a-zA-Z])$'
                            ),
                        ),
                        */
                    ),
                ),
                'email' => array(
                    'name' => 'email',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StripTags'),
                        // Help protect from XSS
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                    'validators' => array(
                        array('name' => 'Zend\Validator\EmailAddress'),
                    ),
                ),
                'name' => array(
                    'name' => 'name',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StripTags'),
                        // Help protect from XSS
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                    'validators' => array(),
                ),
            ),
        ),
        'Auth\Config' => array(
            'ObfuscatePasswordOnAuth' => true,
        ),
        'Acl\Config' => array(

            /*
             * DefaultGuestRoleIds and DefaultUserRoleIds
             * Used by:
             *  RcmUser\Acl\EventListeners
             *
             * These event listeners inject the ACL roles property
             * for a user on the user data events
             * in RcmUser\User\Service\UserDataService.
             */
            'DefaultGuestRoleIds' => array('guest'),
            'DefaultUserRoleIds' => array('user'),
            /*
             * SuperAdminRoleId
             *
             * If this is set, this role will get full permissions always
             * Basically over-rides standard permission handling
             */
            'SuperAdminRoleId' => 'admin',
            /**
             * @todo work this out
             */
            'GuestRoleId' => 'guest',
            /*
             * ResourceProviders
             * Used in:
             *  RcmUser\Acl\Service\AclResourceService
             *
             * This aggregates resources that may be injected by any module,
             * this module wraps the resources
             * in a root resource with common privileges.
             *
             * IMPORTANT:
             * - Parent resources must be first in the resource array
             * - It is not possible to share parent or child resources
             *   between different providers
             *
             * Format for each value of this array is:
             *
             * 'ProviderId(module namespace without back-slashes)' =>
             * 'MyResource/ResourceProvider(extents ResourceProvider)'
             *
             * OR
             *
             * ProviderId(usually module namespace)' => array(
             *     'resourceId' => 'some-resource'
             *     'parentResourceId' => null // Or a parent resourceId if needed
             *     'privileges' => array('privilege1', 'privilege2', 'etc...'),
             *     'name' => 'Human readable or translatable name',
             *     'description' => 'Human readable or translatable description',
             * )
             */
            'ResourceProviders' => array(

                /*
                 * RcmUserAccess
                 * This module inject some of this module's resources.
                 * Also example of a Resource provider
                 */
                'RcmUser' => 'RcmUser\Provider\RcmUserAclResourceProvider',

                /* example of resource providers as array *
                'RcmUser.TEST' => array(
                    'test-one' => array(
                        'resourceId' => 'test-one',
                        'parentResourceId' => null,
                        'privileges' => array(
                            'read',
                            'update',
                            'create',
                            'delete',
                            'execute',
                        ),
                        'name' => 'Test resource one.',
                        'description' => 'test resource one desc.',
                    ),
                    'test-two' => array(
                        'resourceId' => 'test-two',
                        'parentResourceId' => 'test-one',
                        'privileges' => array(
                            'read',
                            'update',
                            'create',
                            'delete',
                            'execute',
                        ),
                        'name' => 'Test resource two.',
                        'description' => 'test resource two desc.',
                    )
                ),
                /* - example */
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            /*
             * Config
             */
            'RcmUser\Config' => 'RcmUser\Service\Factory\Config',
            'RcmUser\User\Config' => 'RcmUser\User\Service\Factory\Config',
            'RcmUser\Auth\Config' => 'RcmUser\Authentication\Service\Factory\Config',
            'RcmUser\Acl\Config' => 'RcmUser\Acl\Service\Factory\Config',
            /* ************************************** */
            /* USER ********************************* */
            /* ************************************** */

            /*
             * UserDataService - Core User data access service
             * Required *
             *
             * This service exposes basic CRUD operations for the User objects.
             */
            'RcmUser\User\Service\UserDataService' =>
                'RcmUser\User\Service\Factory\UserDataService',
            /*
             * UserPropertyService
             * - Allows user properties to be set by event listeners
             * Required *
             *
             * This service allows User properties
             * to be loaded on demand using event listeners.
             * This helps reduce the size of the User object
             * as non-essential properties may be loaded when needed.
             */
            'RcmUser\User\Service\UserPropertyService' =>
                'RcmUser\User\Service\Factory\UserPropertyService',
            /*
             * UserRoleService - Core User Role data access service
             * Required *
             *
             * This service exposes basic CRUD operations for the User roles.
             */
            'RcmUser\User\Service\UserRoleService' =>
                'RcmUser\User\Service\Factory\UserRoleService',
            /*
             * UserDataMapper - Data source adapter
             * Required for:
             *  RcmUser\User\Service\UserDataService
             *
             * This is a DataMapper adapter that is used
             * to abstract the data storage method.
             * This may be configured to use a custom data mapper
             * for unique storage requirements.
             */
            'RcmUser\User\UserDataMapper' =>
                'RcmUser\User\Service\Factory\DoctrineUserDataMapper',
            /* ---------------------------- */
            /*
             * UserRolesDataMapper
             * Required for (ACL user property):
             *  RcmUser\User\Event\UserRoleDataServiceListeners
             *
             * This is a DataMapper adapter that is used
             * to abstract the data storage method.
             * This may be configured to use a custom data mapper
             * for unique storage requirements.
             */
            'RcmUser\User\UserRolesDataMapper' =>
                'RcmUser\User\Service\Factory\DoctrineUserRoleDataMapper',
            /* - Validations - */
            /*
             * UserValidator - Validates User object data on create and update
             * Required for:
             *  RcmUser\User\Db\UserDataMapper (RcmUser\User\UserDataMapper)
             *
             * Uses the InputFilter value from the config by default.
             * This may be configured to use a custom UserValidator as required.
             */
            'RcmUser\User\Data\UserValidator' =>
                'RcmUser\User\Service\Factory\UserValidator',
            /* - Data Prep - */
            /*
             * Encryptor
             * Required for:
             *  RcmUser\User\Data\DbUserDataPreparer
             *  RcmUser\Authentication\Adapter\UserAdapter
             *
             * Used for encrypting/hashing passwords by default.
             * May not be required depending
             * on the DbUserDataPreparer and UserAdapter that is being used.
             */
            'RcmUser\User\Encryptor' => 'RcmUser\User\Service\Factory\Encryptor',
            /*
             * UserDataPreparer
             * Required for:
             *  RcmUser\User\Db\UserDataMapper (RcmUser\User\UserDataMapper)
             *
             * Used by default to prepare data for DB storage.
             * By default, encrypts passwords and creates id (UUID)
             * This may be configured to use a custom UserDataPreparer as required
             */
            'RcmUser\User\Data\UserDataPreparer' =>
                'RcmUser\User\Service\Factory\DbUserDataPreparer',
            /*
             * UserDataServiceListeners
             * Required
             *  to validate, prepare and save (CRUD) User:
             *
             * Requires: RcmUser\User\UserDataMapper
             *
             * Creates event listeners that use the UserValidator
             * to do validation checks on User create and update.
             */
            'RcmUser\User\UserDataServiceListeners' =>
                'RcmUser\User\Service\Factory\UserDataServiceListeners',
            /*
             * UserRoleDataServiceListeners
             * Required for (User Acl Property populating):
             */
            'RcmUser\User\UserRoleDataServiceListeners' =>
                'RcmUser\User\Service\Factory\UserRoleDataServiceListeners',
            /* ************************************** */
            /* AUTH ********************************* */
            /* ************************************** */
            /*
             * UserAuthenticationService
             * Required *
             *
             * Wraps events, actions are preformed in event listeners
             * so that any auth provider may do auth logic.
             */
            'RcmUser\Authentication\Service\UserAuthenticationService' =>
                'RcmUser\Authentication\Service\Factory\UserAuthenticationService',
            /* ---------------------------- */
            /*
             * UserAdapter (requires Encryptor)
             * Required for (Auth):
             *  RcmUser\Authentication\Service\AuthenticationService
             *
             * By default this auth Adapter uses the Encryptor
             * to validate a users credentials
             * This may be configured to use a custom auth Adapter as required
             */
            'RcmUser\Authentication\Adapter' =>
                'RcmUser\Authentication\Service\Factory\Adapter',
            /*
             * UserSession
             * Required for (Auth):
             *  RcmUser\Authentication\Service\AuthenticationService
             *
             * By default this module uses the default session container for storage
             * This may be configured to use a custom Storage object as required
             */
            'RcmUser\Authentication\Storage' =>
                'RcmUser\Authentication\Service\Factory\Storage',
            /*
             * AuthenticationService
             * Required for:
             *  RcmUser\Authentication\EventListeners
             * 
             * By default this module uses the default Adapter and Storage
             * to do authentication
             * This may be configure to use custom AuthenticationService as required
             */
            'RcmUser\Authentication\AuthenticationService' =>
                'RcmUser\Authentication\Service\Factory\AuthenticationService',
            /*
             * EventListeners
             * Used for listening for auth related events:
             *
             * By default this module listens for the events
             * from UserAuthenticationService to do authentication
             * This may be configured to use custom event listeners
             * or disabled if not required
             */
            'RcmUser\Authentication\UserAuthenticationServiceListeners' =>
                'RcmUser\Authentication\Service\Factory\UserAuthenticationServiceListeners',
            /* ************************************** */
            /* ACL ********************************** */
            /* ************************************** */

            /*
             * AclResourceService
             * Used by:
             *  RcmUser\Acl\Provider\ResourceProvider
             *
             * Exposes this module's resource aggregation methods
             */
            'RcmUser\Acl\Service\AclResourceService' =>
                'RcmUser\Acl\Service\Factory\AclResourceService',
            /*
             * AuthorizeService (ACL)
             * Used by:
             *  RcmUserService
             *  ControllerPluginRcmUserIsAllowed
             *  ViewHelperRcmUserIsAllowed
             *
             * Exposes the ACL isAllowed method
             */
            'RcmUser\Acl\Service\AuthorizeService' =>
                'RcmUser\Acl\Service\Factory\AuthorizeService',
            /*
             * AclRoleDataMapper
             * Required
             * This data mapper adapter allows this module
             * to read roles from a data source
             * This may be configured to use a custom data mapper if required
             */
            'RcmUser\Acl\AclRoleDataMapper' =>
                'RcmUser\Acl\Service\Factory\DoctrineAclRoleDataMapper',
            /*
             * AclRuleDataMapper
             * Required for:
             * This data mapper adapter allows this module
             * to read rules from a data source
             * This may be configured to use a custom data mapper if required
             */
            'RcmUser\Acl\AclRuleDataMapper' =>
                'RcmUser\Acl\Service\Factory\DoctrineAclRuleDataMapper',
            /*
             * AclDataService
             * Required for accessing mappers
             * This is designed to expose a simple facade
             * for use in displaying and updating ACL data
             * in views
             */
            'RcmUser\Acl\AclDataService' =>
                'RcmUser\Acl\Service\Factory\AclDataService',
            /* ************************************** */
            /* CORE ********************************* */
            /* ************************************** */
            /*
             * Main service facade
             * Uses:
             *  UserDataService
             *  UserPropertyService
             *  UserAuthenticationService
             *  AuthorizeService
             */
            'RcmUser\Service\RcmUserService' =>
                'RcmUser\Service\Factory\RcmUserService',
            /*
             * Provides the Access Resources for this Module to ACL
             * Required *
             */
            'RcmUser\Provider\RcmUserAclResourceProvider' =>
                'RcmUser\Service\Factory\RcmUserAclResourceProvider',
            /*
             * Event Aggregation
             * Required *
             */
            'RcmUser\Event\Listeners' => 'RcmUser\Service\Factory\EventListeners',
            /*
             * Logging
             * Required *
             */
            'RcmUser\Log\Logger' => 'RcmUser\Service\Factory\DoctrineLogger',
        ),
    ),
    /*
     * Allows doctrine to generate tables as needed
     * Only required if using doctrine entities and mappers
     * And you want doctrine utilities to work correctly
     */
    'doctrine' => array(
        'driver' => array(
            'RcmUser' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/RcmUser/Acl/Entity',
                    __DIR__ . '/../src/RcmUser/User/Entity',
                    __DIR__ . '/../src/RcmUser/Log/Entity',
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'RcmUser' => 'RcmUser'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            // TESTING
            'RcmUser\Controller\UserTestController' =>
                'RcmUser\Controller\UserTestController',
            // ADMIN GENERAL
            'RcmUser\Controller\AdminJsController' =>
                'RcmUser\Controller\AdminJsController',
            // ADMIN ACL
            'RcmUser\Controller\AdminAclController' =>
                'RcmUser\Controller\AdminAclController',
            'RcmUser\Controller\AdminApiAclResourcesController' =>
                'RcmUser\Controller\AdminApiAclResourcesController',
            'RcmUser\Controller\AdminApiAclRulesByRolesController' =>
                'RcmUser\Controller\AdminApiAclRulesByRolesController',
            'RcmUser\Controller\AdminApiAclRuleController' =>
                'RcmUser\Controller\AdminApiAclRuleController',
            'RcmUser\Controller\AdminApiAclRoleController' =>
                'RcmUser\Controller\AdminApiAclRoleController',
            'RcmUser\Controller\AdminApiAclDefaultUserRoleController' =>
                'RcmUser\Controller\AdminApiAclDefaultUserRoleController',
            // ADMIN USERS
            'RcmUser\Controller\AdminUserController' =>
                'RcmUser\Controller\AdminUserController',
            'RcmUser\Controller\AdminApiUserController' =>
                'RcmUser\Controller\AdminApiUserController',
            'RcmUser\Controller\AdminApiUserValidUserStatesController' =>
                'RcmUser\Controller\AdminApiUserValidUserStatesController',
            // ADMIN USER ROLES
            /*'RcmUser\Controller\AdminUserRoleController'
            => 'RcmUser\Controller\AdminUserRoleController',*/
            'RcmUser\Controller\AdminApiUserRolesController' =>
                'RcmUser\Controller\AdminApiUserRolesController',
            'RcmUser\Controller\AdminApiUserRoleController' =>
                'RcmUser\Controller\AdminApiUserRoleController',
        ),
    ),
    'router' => array(
        'routes' => array(
            // GENERAL
            /**
             * TEST CONTROLLER - TESTING ONLY
             *
             * @view
             */
            'RcmUser' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/rcmusertest',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\UserTestController',
                        'action' => 'index',
                    ),
                ),
            ),
            // ADMIN GENERAL
            /**
             * RcmUserAdminCss
             * General Admin CSS
             *
             * @view CSS
             */
            'RcmUserCoreJs' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser/js/core.js',
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminJsController',
                        'action' => 'adminCore',
                    ),
                ),
            ),
            // ADMIN ACL
            /**
             * RcmUserAdminAcl
             * View for creating and editing roles and rule
             *
             */
            'RcmUserAdminAcl' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser-acl',
                    'constraints' => array(),
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminAclController',
                        'action' => 'index',
                    ),
                ),
            ),
            /**
             * RcmUserAdminAclJs
             * JavaScript for RcmUserAdminAcl
             */
            'RcmUserAdminAclJs' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser/js/admin-acl.js',
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminJsController',
                        'action' => 'adminAcl',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiAclResources
             * Get resources
             *
             * @api
             */
            'RcmUserAdminApiAclResources' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-acl-resources[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiAclResourcesController',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiAclRulesByRoles
             * Returns Roles and the related Rules
             *
             * @api
             */
            'RcmUserAdminApiAclRulesByRoles' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-acl-rulesbyroles[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiAclRulesByRolesController',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiAclRule
             * Return rules and exposes create and delete
             *
             * @api
             */
            'RcmUserAdminApiAclRule' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-acl-rule[/:id]',
                    //'constraints' => array(
                    //'id' => '[a-zA-Z0-9_-]+',
                    //),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiAclRuleController',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiAclRole
             * Return roles and exposes create and delete
             *
             * @api
             */
            'RcmUserAdminApiAclRole' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-acl-role[/:id]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiAclRoleController',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiAclDefaultUserRole
             * Return default User roles
             *
             * @api
             */
            'RcmUserAdminApiAclDefaultUserRole' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-acl-default-user-roles[/:id]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiAclDefaultUserRoleController',
                    ),
                ),
            ),
            // ADMIN USERS
            /**
             * RcmUserAdminUsers
             * View for creating and editing users
             */
            'RcmUserAdminUsers' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser-users',
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminUserController',
                        'action' => 'index',
                    ),
                ),
            ),
            /**
             * RcmUserAdminUserJs
             * JavaScript for RcmUserAdminUsers
             */
            'RcmUserAdminUserJs' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser/js/admin-users.js',
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminJsController',
                        'action' => 'adminUsers',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiUser
             * API for creating and editing users
             *
             * @api
             */
            'RcmUserAdminApiUser' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-user[/:id]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiUserController',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiUserValidUserStates
             * API to get list of valid user states
             *
             * @api
             */
            'RcmUserAdminApiUserValidUserStates' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-user-validuserstates[/:id]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiUserValidUserStatesController',
                    ),
                ),
            ),
            /* ADMIN USER ROLES
            'RcmUserAdminUserRole' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser-user-role/:id',
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminUserRoleController',
                        'action' => 'index',
                    ),
                ),
            ),
            'RcmUserAdminUserRoleJs' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/rcmuser/js/admin-user-role.js',
                    'defaults' => array(
                        'controller' => 'RcmUser\Controller\AdminJsController',
                        'action' => 'adminUserRoles',
                    ),
                ),
            ),
            */
            /**
             * RcmUserAdminApiUserRoles
             * API for listing, creating and deleting user roles as a group
             *
             * @api
             */
            'RcmUserAdminApiUserRoles' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-user-roles[/:id]',
                    //'constraints' => array(
                    //'id' => '[a-zA-Z0-9_-]+',
                    //),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiUserRolesController',
                    ),
                ),
            ),
            /**
             * RcmUserAdminApiUserRoles
             * API creating and deleting an individual user role
             *
             * @api
             */
            'RcmUserAdminApiUserRole' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/admin/rcmuser-user-role[/:id]',
                    //'constraints' => array(
                    //'id' => '[a-zA-Z0-9_-]+',
                    //),
                    'defaults' => array(
                        'controller' =>
                            'RcmUser\Controller\AdminApiUserRoleController',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'RcmUser' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'rcmUserIsAllowed' =>
                'RcmUser\Service\Factory\ControllerPluginRcmUserIsAllowed',
            'rcmUserHasRoleBasedAccess' =>
                'RcmUser\Service\Factory\ControllerPluginRcmUserHasRoleBasedAccess',
            'rcmUserGetCurrentUser' =>
                'RcmUser\Service\Factory\ControllerPluginRcmUserGetCurrentUser',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'rcmUserIsAllowed' =>
                'RcmUser\View\Service\Factory\ViewHelperRcmUserIsAllowed',
            'rcmUserHasRoleBasedAccess' =>
                'RcmUser\View\Service\Factory\ViewHelperRcmUserHasRoleBasedAccess',
            'rcmUserBuildHtmlHead' =>
                'RcmUser\View\Service\Factory\ViewHelperRcmUserBuildHtmlHead',
            'rcmUserGetCurrentUser' =>
                'RcmUser\View\Service\Factory\ViewHelperRcmUserGetCurrentUser',
        ),
    ),

    /**
     * Config for allowing dynamic loading of public assets
     */
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-user/' => __DIR__ . '/../public/',
            ],
            // @todo implement this
            //'collections' => [
            //    '/modules/rcm-user/rcm-user.js' => [
            //    ],
            //    '/modules/admin/rcm-user/rcm-user.js' => [
            //    ],
            //],
        ],
    ],
);
