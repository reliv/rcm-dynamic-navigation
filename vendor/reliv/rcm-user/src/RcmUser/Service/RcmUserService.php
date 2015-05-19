<?php
/**
 * Class RcmUserService
 *
 * RcmUserService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Service;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\User;
use RcmUser\User\Result;
use RcmUser\User\Service\UserDataService;
use RcmUser\User\Service\UserPropertyService;

//use ZfcUser\Service\User;

/**
 * Class RcmUserService
 *
 * RcmUserService facade
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserService extends \RcmUser\Event\EventProvider
{

    /**
     * @var UserDataService
     */
    protected $userDataService;

    /**
     * @var UserPropertyService
     */
    protected $userPropertyService;

    /**
     * @var UserAuthenticationService
     */
    protected $userAuthService;

    /*
     * ACL
     * @var AuthorizeService
     */
    protected $authorizeService;

    /**
     * setUserDataService
     *
     * @param UserDataService $userDataService userDataService
     *
     * @return void
     */
    public function setUserDataService(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }

    /**
     * getUserDataService
     *
     * @return UserDataService
     */
    public function getUserDataService()
    {
        return $this->userDataService;
    }

    /**
     * setUserPropertyService
     *
     * @param UserPropertyService $userPropertyService userPropertyService
     *
     * @return void
     */
    public function setUserPropertyService(
        UserPropertyService $userPropertyService
    ) {
        $this->userPropertyService = $userPropertyService;
    }

    /**
     * getUserPropertyService
     *
     * @return UserPropertyService
     */
    public function getUserPropertyService()
    {
        return $this->userPropertyService;
    }

    /**
     * setUserAuthService
     *
     * @param UserAuthenticationService $userAuthService userAuthService
     *
     * @return void
     */
    public function setUserAuthService(
        UserAuthenticationService $userAuthService
    ) {
        $this->userAuthService = $userAuthService;
    }

    /**
     * getUserAuthService
     *
     * @return UserAuthenticationService
     */
    public function getUserAuthService()
    {
        return $this->userAuthService;
    }

    /**
     * setAuthorizeService: ACL Service
     *
     * @param AuthorizeService $authorizeService authorizeService
     *
     * @return void
     */
    public function setAuthorizeService(
        AuthorizeService $authorizeService
    ) {
        $this->authorizeService = $authorizeService;
    }

    /**
     * getAuthorizeService: ACL service
     *
     * @return AuthorizeService
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }

    /** HELPERS ***************************************/

    /**
     * getUser
     * returns a user from the data source
     * based on the data in the provided User object (User::id and User::username)
     *
     * @param User $user request user object
     *
     * @return null|User
     */
    public function getUser(User $user)
    {
        // @todo - check all sources (db and session)?
        $result = $this->readUser($user);

        if ($result->isSuccess()) {
            return $result->getUser();
        }

        return null;
    }

    /**
     * getUserById
     *
     * @param $userId
     *
     * @return null|User
     */
    public function getUserById($userId)
    {

        $requestUser = $this->buildNewUser();
        $requestUser->setId($userId);

        return $this->getUser($requestUser);
    }

    /**
     * getUserByUsername
     *
     * @param $userName
     *
     * @return null|User
     */
    public function getUserByUsername($userName)
    {

        $requestUser = $this->buildNewUser();
        $requestUser->setUsername($userName);

        return $this->getUser($requestUser);
    }

    /**
     * userExists
     * returns true if the user exists in the data source
     *
     * @param User $user request user object
     *
     * @return bool
     */
    public function userExists(User $user)
    {
        $result = $this->readUser($user);

        return $result->isSuccess();
    }

    /* CRUD HELPERS ***********************************/

    /**
     * readUser
     *
     * @param User $user          request user object
     * @param bool $includeResult If true, will return data in result object
     *
     * @return Result|User|null
     */
    public function readUser(
        User $user,
        $includeResult = true
    ) {
        $result = $this->getUserDataService()->readUser($user);

        if ($includeResult) {
            return $result;
        } else {
            return $result->getData();
        }
    }

    /**
     * createUser
     *
     * @param User $user          request user object
     * @param bool $includeResult If true, will return data in result object
     *
     * @return Result|User|null
     */
    public function createUser(
        User $user,
        $includeResult = true
    ) {
        $result = $this->getUserDataService()->createUser($user);

        if ($includeResult) {
            return $result;
        } else {
            return $result->getData();
        }
    }

    /**
     * updateUser
     *
     * @param User $user          request user object
     * @param bool $includeResult If true, will return data in result object
     *
     * @return Result|User|null
     */
    public function updateUser(
        User $user,
        $includeResult = true
    ) {
        $result = $this->getUserDataService()->updateUser($user);
        if ($includeResult) {
            return $result;
        } else {
            return $result->getData();
        }
    }

    /**
     * deleteUser
     *
     * @param User $user          request user object
     * @param bool $includeResult If true, will return data in result object
     *
     * @return Result|User|null
     */
    public function deleteUser(
        User $user,
        $includeResult = true
    ) {
        $result = $this->getUserDataService()->deleteUser($user);
        if ($includeResult) {
            return $result;
        } else {
            return $result->getData();
        }
    }

    /* PROPERTY HELPERS *******************************/

    /**
     * getUserProperty
     * OnDemand loading of a user property.
     * Is a way of populating User::property using events.
     * Some user properties are not loaded with the user to increase speed.
     * Use this method to load these properties.
     *
     * @param User   $user              request user object
     * @param string $propertyNameSpace unique id of the requested property
     * @param mixed  $default           return value if property not set
     * @param bool   $refresh           will force retrieval of property
     *
     * @return mixed
     */
    public function getUserProperty(
        User $user,
        $propertyNameSpace,
        $default = null,
        $refresh = false
    ) {
        return $this->getUserPropertyService()->getUserProperty(
            $user,
            $propertyNameSpace,
            $default,
            $refresh
        );
    }

    /**
     * getCurrentUserProperty
     *
     * @param string $propertyNameSpace propertyNameSpace
     * @param mixed  $default           return value if property not set
     * @param bool   $refresh           refresh
     *
     * @return mixed
     */
    public function getCurrentUserProperty(
        $propertyNameSpace,
        $default = null,
        $refresh = false
    ) {
        $user = $this->getIdentity();

        if (empty($user)) {
            return $default;
        }

        return $this->getUserProperty(
            $user,
            $propertyNameSpace,
            $default,
            $refresh
        );

    }

    /**
     * disableUser @todo WRITE THIS
     *
     * @param User $user user
     *
     * @return void
     */
    public function disableUser(User $user)
    {
    }

    /* AUTHENTICATION HELPERS ********************************/

    /**
     * validateCredentials
     * Allows the validation of user credentials (username and password)
     * without creating an auth session.
     * Helpful for doing non-login authentication checks.
     *
     * @param User $user request user object
     *
     * @return Result
     */
    public function validateCredentials(User $user)
    {
        return $this->getUserAuthService()->validateCredentials($user);
    }

    /**
     * authenticate
     * Creates auth session (logs in user)
     * if credentials provided in the User object are valid.
     *
     * @param User $user request user object
     *
     * @return Result
     */
    public function authenticate(User $user)
    {
        return $this->getUserAuthService()->authenticate($user);
    }

    /**
     * clearIdentity
     * Clears auth session (logs out user)
     *
     * @return void
     */
    public function clearIdentity()
    {
        return $this->getUserAuthService()->clearIdentity();
    }

    /**
     * hasIdentity
     * Check if any User is auth'ed (logged in)
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getUserAuthService()->hasIdentity();
    }

    /**
     * isIdentity
     * Check if the requested user in the user that is currently in the auth session
     *
     * @param User $user request user object
     *
     * @return bool
     */
    public function isIdentity(User $user)
    {
        $sessUser = $this->getIdentity();

        if (empty($sessUser)) {
            return false;
        }

        // @todo make sure this is a valid check for all cases
        $id = $user->getId();
        if (!empty($id)
            && $user->getId() === $sessUser->getId()
        ) {
            return true;
        }

        $username = $user->getUsername();
        if (!empty($username)
            && $user->getUsername() === $sessUser->getUsername()
        ) {
            return true;
        }

        return false;
    }

    /**
     * setIdentity
     * Force a User into the auth'd session.
     * - WARNING: this by-passes the authentication process
     *            and should only be used with extreme caution
     *
     * @param User $user request user object
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setIdentity(User $user)
    {
        $currentUser = $this->getIdentity();

        if (empty($currentUser) || $user->getId() !== $currentUser->getId()) {

            throw new RcmUserException(
                'SetIdentity expects user to be get same identity as current, '
                . 'user authenticate to change users.'
            );
        }

        return $this->getUserAuthService()->setIdentity($user);
    }

    /**
     * refreshIdentity
     * Will reload the current User that is Auth'd into the auth'd session.
     * Is a way of refreshing the session user without log-out, then log-in
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function refreshIdentity()
    {
        $currentUser = $this->getIdentity();

        if (empty($currentUser)) {
            return null;
        }

        $result = $this->readUser($currentUser);

        if (!$result->isSuccess()) {
            return null;
        }

        $user = $result->getUser();

        $userId = $user->getId();

        if ($userId != $currentUser->getId()) {

            throw new RcmUserException(
                'RefreshIdentity expects user to be get same identity as current.'
            );
        }

        return $this->getUserAuthService()->setIdentity($user);
    }

    /**
     * getIdentity
     * Get the current User (logged in User) from Auth'd session
     * or returns $default is there is no User Auth'd
     *
     * @param mixed $default return this value if no User is auth'd
     *
     * @return User|null
     */
    public function getIdentity($default = null)
    {
        return $this->getUserAuthService()->getIdentity($default);
    }

    /**
     * getCurrentUser
     *  - @alias getIdentity
     *
     * @param mixed $default default
     *
     * @return User|null
     */
    public function getCurrentUser($default = null)
    {
        $user = $this->getIdentity($default);

        return $user;
    }

    //@todo implement guestIdentity
    // - if getIdentity is empty return guest and save updates in session
    // on login we can sync the guest user or the session user as needed

    /* ACL HELPERS ********************************/

    /**
     * isAllowed
     * Check if the current Auth'd User has
     * access to a resource with a privilege provided by provider id.
     * This is use to validate a users access
     * based on their role and the rules set by ACL
     *
     * @param string $resourceId a string resource id as defined by a provider
     * @param string $privilege  privilege of the resource to check
     * @param string $providerId resource unique identifier of the resource provider
     *
     * @return bool
     */
    public function isAllowed(
        $resourceId,
        $privilege = null,
        $providerId = null
    ) {
        $user = $this->getIdentity();

        return $this->isUserAllowed(
            $resourceId,
            $privilege,
            $providerId,
            $user
        );
    }

    /**
     * isUserAllowed
     * Check if the current Auth'd User has
     * access to a resource with a privilege provided by provider id.
     * This is use to validate a users access
     * based on their role and the rules set by ACL
     *
     * @param string $resourceId a string resource id as defined by a provider
     * @param string $privilege  privilege of the resource to check
     * @param string $providerId resource unique identifier of the resource provider
     * @param User   $user       request user object
     *
     * @return mixed
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function isUserAllowed(
        $resourceId,
        $privilege = null,
        $providerId = null,
        $user = null
    ) {
        return $this->getAuthorizeService()->isAllowed(
            $resourceId,
            $privilege,
            $providerId,
            $user
        );
    }

    /**
     * hasRoleBasedAccess
     * Check if current user has access based on role inheritance
     *
     * @param $roleId
     *
     * @return bool
     */
    public function hasRoleBasedAccess($roleId)
    {
        $user = $this->getIdentity();

        if (!($user instanceof User)) {
            return false;
        }

        return $this->hasUserRoleBasedAccess($user, $roleId);
    }

    /**
     * hasUserRoleBasedAccess -
     * Check if a user has access based on role inheritance
     *
     * @param User $user
     * @param string $roleId
     *
     * @return bool
     */
    public function hasUserRoleBasedAccess($user, $roleId)
    {
        if (!($user instanceof User)) {
            return false;
        }

        return $this->getAuthorizeService()->hasRoleBasedAccess($user, $roleId);
    }

    /* UTILITIES **************************************/
    /**
     * buildNewUser
     * Factory method to build new User object
     * populated with defaults from event listeners
     *
     * @return User
     */
    public function buildNewUser()
    {
        $user = new User();

        return $this->buildUser($user);
    }

    /**
     * buildUser
     * Populate a User with defaults from event listeners
     *
     * @param User $user request user object
     *
     * @return User
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function buildUser(User $user)
    {
        $result = $this->getUserDataService()->buildUser($user);

        // since build user is an event, we might not get anything
        if (empty($result)) {
            return $user;
        }

        if ($result->isSuccess() || $result->getUser() == null) {
            return $result->getUser();
        } else {

            // this should not fail, if it does, something is really wrong
            throw new RcmUserException(
                'User could not be built or was not returned'
            );
        }
    }
}
