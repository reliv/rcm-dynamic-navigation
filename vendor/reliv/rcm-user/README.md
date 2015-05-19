RcmUser
=======

Introduction
------------

The main goal of this module is to expose a simple and configurable user object as well as the services related to user storage, access control and authentication.

See TODO.md for future goals.


Features
--------

### UI ###

There are a limited amount of included HTML views/pages.

Views are designed using Twitter Bootstrap and AngularJS.

Views are design to be mostly independent of the framework (MVC move to Angular and data is deliver VIA REST/JSON API).

#### Available Views ####

- RcmUserAdminAcl:
 - View for creating and editing roles and rules
 - Requires access to resource: rcmuser-acl-administration

- RcmUserAdminUsers:
 - View for administrating User data
 - Requires access to resource: rcmuser-user-administration

#### REST/JSON APIs ####

This module exposes several APIs for user administration.
The APIs are not comprehensive, but they do allow for some user and ACL management.
The admin APIs require access rules to be set in order to access (@see ACL section).

For a complete list of the APIs, please see the RcmUser/config/module.config.php file, routes section.
API standard return is a result object containing a code, message and the data.

### User ###

#### User class ####

The User class is the module's main user entity.

The User class has the properties of:

- id
 - A unique identifier, by default this is generated on create by the DbUserDataPreparer.

- username
 - A unique username.

- password
 - A password, by default this is hashed by the Encryptor on create/update by the DbUserDataPreparer.
 - The Auth UserAdapter also uses the same Encryptor to authenticate password.

- state
 - State is used to provide a tag for the users state.
 - There is only one state provided ('disabled'), any other state my be created and utilized as needed.

- email
 - An email address
 - Validations may be set in the config
 - By default this is not required to be unique

- name
 - A display name
 - Validations may be set in the config

- properties
 - An aggregation of arbitrary properties
 - These can be injected into the User object by using event listeners for the User data events or the property events.
 - These can also be injected directly in the data mappers if you provide your own.

 By default, there is a ACL roles property injected for the User.  This property is the one which is used by this module's ACL.

### RcmUserService ###

RcmUserService is a high level service/facade that exposes many useful methods for manipulating a User object.

- PHP APIs that a developer might need regularly
- Methods for basic create, read, update, delete
- Methods for accessing User->properties on demand (properties that are only populated as needed)
- Methods for doing authentication of user (log in, log out, credential checks)
- Methods for checking user access (ACL)
- Other utility and helpful methods are also provided

##### Data Methods #####

- getUser(User $user)
 - Returns a user from the data source based on the data in the provided User object (User::id and User::username)

- userExists(User $user)
 - Returns true if the user exists in the data source

- readUser(User $user, $includeResult = true)
 - Returns a Result object containing the a User object if user is found, null if not found.
 - Returns the User object or null if $includeResult == false

- createUser(User $user, $includeResult = true)
 - Returns a Result object containing the a User object if user is created with a success message.
 - Returns the User object if $includeResult == false

- updateUser(User $user, $includeResult = true)
 - Returns a Result object containing the a User object if user is updated with a success message.
 - Returns the User object if $includeResult == false
  - $user = {request user object}
  - $includeResult = {if true, will return Result Object containing code, message and data (User)}

- deleteUser(User $user, $includeResult = true)
 - Returns a Result object containing the an  empty User object if user is updated with a success message.
 - Returns the User object if $includeResult == false
 - $user = {request user object}
 - $includeResult = {if true, will return Result Object containing code, message and data (User)}

- getUserProperty(User $user, $propertyNameSpace, $default = null, $refresh = false)
 - OnDemand loading of a user property.  I a way of populating User::property using events.
 - Some user properties are not loaded with the user to increase speed.  Use this method to load these properties.
 - $user = {request user object}
 - $propertyNameSpace = {unique id of the requested property}
 - $default = {return value if property not set}
 - $refresh = {will force retrieval of property, even if it is already set}

- getCurrentUserProperty($propertyNameSpace, $default = null, $refresh = false)
 - OnDemand loading of a CURRENT user property.  I a way of populating User::property using events.
 - Some user properties are not loaded with the user to increase speed.  Use this method to load these properties.
 - $propertyNameSpace = {unique id of the requested property}
 - $default = {return value if property not set}
 - $refresh = {will force retrieval of property, even if it is already set}

##### Authentication Methods #####

- validateCredentials(User $user)
 - Allows the validation of user credentials (username and password) without creating an auth session.
 - Helpful for doing non-login authentication checks.
 - $user = {request user object}

- authenticate(User $user)
 - Creates auth session (logs in user) if credentials provided in the User object are valid.
 - $user = {request user object}

- clearIdentity()
 - Clears auth session (logs out user)

- hasIdentity()
 - Check if any User is auth'ed (logged in)

- isIdentity(User $user)
 - Check if the requested user in the user that is currently in the auth session
 - $user = {request user object}

- setIdentity(User $user)
 - Force a User into the auth'd session.
 - $user = {request user object}
 - WARNING: this by-passes the authentication process and should only be used with extreme caution

- refreshIdentity()
 - Will reload the current User that is Auth'd into the auth'd session.
 - Is a way of refreshing the session user without log-out, then log-in

- getIdentity($default = null)
 - Get the current User (logged in User) from Auth'd session or returns $default is there is no User Auth'd
 - $default = {return this value if no User is auth'd}

##### Access Control Methods ACL (Access Control Layer) Based #####

- isAllowed($resourceId, $privilege = null, $providerId = null)
 - Check if the current Auth'd User has access to a resource with a privilege provided by provider id.
 - This is use to validate a users access based on their role and the rules set by ACL
 - $resourceId = {a string resource id as defined by a resource provider (may be another module)}
 - $privilege = {a privilege of the resource to check access against for this User},
 - $providerId = {unique identifier of the provider of the resource and privilege definition}

- isUserAllowed($resourceId, $privilege = null, $providerId = null, $user = null)
 - Check if the requested User has access to a resource with a privilege provided by provider id.
 - This is use to validate a users access based on their role and the rules set by ACL
 - $resourceId = {a string resource id as defined by a resource provider (may be another module)}
 - $privilege = {a privilege of the resource to check access against for this User},
 - $providerId = {unique identifier of the provider of the resource and privilege definition}
 - $user = {request user object}
 
##### Access Control Methods RBA (Role Based Access) #####

- hasRoleBasedAccess($roleId)
 - Check if the current Auth'd User has the requested role or the role exists in the role lineage
 - Returns Bool
 
- hasUserRoleBasedAccess($user, $roleId)
 - Check if the requested User has the requested role or the role exists in the role lineage
 - Returns Bool

##### Utility Methods #####

- buildNewUser()
 - Factory method to build new User object populated with defaults from event listeners

- buildUser(User $user)
 - Populate a User with defaults from event listeners
 - $user = {request user object}

#### DataMappers ####

The UserMappers are adapters used to populate and store the user data.
By default this module uses the Doctrine DataMappers.
Any data mapper can be written and configured so that data may be stored based on your requirements.

> NOTE: If you decide to write you own data mappers, you may find the implementation test (W.I.P.) in RcmUser/test/ImplementationTest helpful.
> The implementation test is NOT to be run in PROD as it creates and destroys data.

#### Controller Plugins and View Helpers ####

- rcmUserIsAllowed($resourceId, $privilege = null, $providerId = null) (plugin and helper)
 - Alias of RcmUserService::isAllowed()

- rcmUserHasRoleBasedAccess($roleId) (plugin and helper)
 - Alias of RcmUserService::hasRoleBasedAccess()
 
- rcmUserGetCurrentUser($default = null) (plugin and helper)
 - Alias of RcmUserService::getIdentity()

### Authentication ###

This module uses the ZF2 Authentication libraries.  This requires it to provide:

- AuthenticationService
 - By default, this module uses the ZF2 class without modification.
 - You may inject your own as required.
- Adapter
 - By default, this module uses it's UserAdapter which requires Encryptor and UserDataService.
 - You may inject your own as required.
- Storage
 - By default, this module uses UserSession which is a session storage with $namespace = 'RcmUser', $member = 'user' and the default session container.

### ACL ###

This module wraps resources in a root schema and provides data mappers for storage of roles and rules.
This module also provides a service, controller plug-in and view helper for isAllowed (rcmUserIsAllowed for plug-in and helper)

This module also creates some ACL resources that are used to allow access to APIs and Views provided in this Module.

ProviderId:
- RcmUser

Resources and Privileges:
- rcmuser

 - rcmuser-user-administration
      - read
      - update
      - create
      - delete
      - update_credentials

 - rcmuser-acl-administration
      - read
      - update
      - create
      - delete

Requirements
------------

- php 5.5.* (not tested on lesser versions)
- zendframework 2.2.x
- rwoverdijk/assetmanager 1.*

Optional based on configuration

- doctrine 2.x
- mysql 5.6.x (not tested on lesser versions)

View dependencies

- AngularJs (https://angularjs.org/)
- Bootstrap (http://getbootstrap.com/)
- UI Bootstrap (http://angular-ui.github.io/bootstrap/)

Installation
------------

### Manual Install ###

- Download or clone from GitHub into your ZF2 skeleton app
- Configure module
- Run install.sql (as needed)

### Composer Install ###

@future

Configuration
-------------

### Module Config Tree ###

```php
<?php
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
)

```