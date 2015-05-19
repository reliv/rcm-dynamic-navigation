<?php
/**
 * UserRoleDataServiceListeners.php
 *
 * UserRoleDataServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Event;

use
    RcmUser\User\Entity\User;
use
    RcmUser\User\Entity\UserRoleProperty;
use
    RcmUser\User\Result;
use
    RcmUser\User\Service\UserRoleService;

/**
 * UserRoleDataServiceListeners
 *
 * UserRoleDataServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserRoleDataServiceListeners extends AbstractUserDataServiceListeners
{
    /**
     * @var int $priority
     */
    protected $priority = 1;
    /**
     * @var array $listenerMethods
     */
    protected $listenerMethods
        = [
            'onGetAllUsersSuccess' => 'getAllUsersSuccess',
            'onBuildUser' => 'buildUser',
            //'onBeforeCreateUser' => 'beforeCreateUser',
            'onCreateUserSuccess' => 'createUserSuccess',
            'onReadUserSuccess' => 'readUserSuccess',
            //'onBeforeUpdateUser' => 'beforeUpdateUser',
            'onUpdateUserSuccess' => 'updateUserSuccess',
            'onDeleteUserSuccess' => 'deleteUserSuccess',
        ];

    /**
     * @var UserRoleService $userRoleService
     */
    protected $userRoleService;

    /**
     * setUserRoleService
     *
     * @param UserRoleService $userRoleService userRoleService
     *
     * @return void
     */
    public function setUserRoleService(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    /**
     * getUserRoleService
     *
     * @return UserRoleService
     */
    public function getUserRoleService()
    {
        return $this->userRoleService;
    }

    /**
     * getUserPropertyKey
     *
     * @return string
     */
    public function getUserPropertyKey()
    {
        return UserRoleProperty::PROPERTY_KEY;
    }

    /**
     * onGetAllUsersSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onGetAllUsersSuccess($e)
    {
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $users = $result->getData();

        foreach ($users as &$user) {

            $readResult = $this->getUserRoleService()->readRoles($user);

            if ($readResult->isSuccess()) {

                $roles = $readResult->getData();
            } else {

                $result->setMessage(
                    'Could not read user roles: ' . $readResult->getMessage()
                );

                $roles = [];
            }

            $userRoleProperty = $this->buildValidUserRoleProperty(
                $user,
                $roles
            );

            $user->setProperty(
                $this->getUserPropertyKey(),
                $userRoleProperty
            );

        }

        $result->setData($users);

        return $result;
    }

    /**
     * onBuildUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBuildUser($e)
    {
        // $requestUser = $e->getParam('requestUser');
        /** @var User $responseUser */
        $responseUser = $e->getParam('responseUser');

        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            null
        );

        if (!$userRoleProperty instanceof UserRoleProperty) {

            $userRoleProperty = $this->buildValidUserRoleProperty(
                $responseUser,
                []
            );
        }

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        return new Result($responseUser);
    }

    /**
     * onBeforeCreateUser
     *
     * @param Event $e e
     *
     * @return Result
    public function onBeforeCreateUser($e)
     * {
     * $requestUser = $e->getParam('requestUser');
     * $responseUser = $e->getParam('responseUser');
     *
     * // VALIDATE //
     * $aclRoles = $responseUser->getProperty(
     * $this->getUserPropertyKey()
     * );
     *
     * if (!empty($aclRoles)) {
     *
     * // @todo Validation logic here
     * // make sure the role sent in is valid
     * }
     *
     * return new Result($responseUser);
     * }
     */

    /**
     * onCreateUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onCreateUserSuccess($e)
    {
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        /** @var $userRoleProperty \RcmUser\User\Entity\UserRoleProperty */
        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            new UserRoleProperty([])
        );

        $userRoleProperty = $this->buildValidUserRoleProperty(
            $responseUser,
            $userRoleProperty->getRoles()
        );

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        $roles = $userRoleProperty->getRoles();

        $saveRoles = $this->removeDefaultUserRoleIds($roles);

        $createResult = $this->getUserRoleService()->createRoles(
            $responseUser,
            $saveRoles
        );

        if (!$createResult->isSuccess()) {
            return new Result($responseUser, Result::CODE_FAIL, $createResult->getMessages(
            ));
        }

        return new Result($responseUser, Result::CODE_SUCCESS);

    }

    /**
     * onReadUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onReadUserSuccess($e)
    {
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        $readResult = $this->getUserRoleService()->readRoles($responseUser);

        if (!$readResult->isSuccess()) {
            return new Result($responseUser, Result::CODE_FAIL, $readResult->getMessages(
            ));
        }

        $roles = $readResult->getData();

        $userRoleProperty = $this->buildValidUserRoleProperty(
            $responseUser,
            $roles
        );

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * onBeforeUpdateUser
     *
     * @param Event $e e
     *
     * @return Result

    public function onBeforeUpdateUser($e)
     * {
     * $requestUser = $e->getParam('requestUser');
     * $responseUser = $e->getParam('responseUser');
     * $existingUser = $e->getParam('existingUser');
     *
     * // VALIDATE //
     * $userRoleProperty = $responseUser->getProperty(
     * $this->getUserPropertyKey(),
     * new UserRoleProperty(array())
     * );
     *
     * $aclRoles = $userRoleProperty->getRoles();
     *
     * if (!empty($aclRoles)) {
     *
     * // @todo Validation logic here
     * // make sure the role sent in is valid
     * }
     *
     * return new Result($responseUser);
     * }
     */

    /**
     * onUpdateUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onUpdateUserSuccess($e)
    {
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        /** @var $userRoleProperty \RcmUser\User\Entity\UserRoleProperty */
        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            new UserRoleProperty([])
        );

        $userRoleProperty = $this->buildValidUserRoleProperty(
            $responseUser,
            $userRoleProperty->getRoles()
        );

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        $roles = $userRoleProperty->getRoles();

        $saveRoles = $this->removeDefaultUserRoleIds($roles);

        // do update
        $updateResult = $this->getUserRoleService()->updateRoles(
            $responseUser,
            $saveRoles
        );

        if (!$updateResult->isSuccess()) {
            return new Result($responseUser, Result::CODE_FAIL, $updateResult->getMessages(
            ));
        }

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * onDeleteUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onDeleteUserSuccess($e)
    {
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        /** @var $userRoleProperty \RcmUser\User\Entity\UserRoleProperty */
        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            $this->buildUserRoleProperty([])
        );

        $deleteResult = $this->getUserRoleService()->deleteRoles(
            $responseUser,
            $userRoleProperty->getRoles()
        );

        if (!$deleteResult->isSuccess()) {
            return new Result($responseUser, Result::CODE_FAIL, $deleteResult->getMessages(
            ));
        }

        $userRoleProperty->setRoles([]);

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * removeDefaultUserRoleIds
     *
     * @param array $roles roles
     *
     * @return array
     */
    public function removeDefaultUserRoleIds($roles = [])
    {
        $defaultRolesResult = $this->getUserRoleService()->getDefaultUserRoleIds();

        $defaultRoles = $defaultRolesResult->getData();

        return array_diff(
            $roles,
            $defaultRoles
        );
    }

    /**
     * buildUserRoleProperty
     *
     * @param array $roles roles
     *
     * @return UserRoleProperty
     */
    public function buildUserRoleProperty($roles = [])
    {
        return $this->getUserRoleService()->buildUserRoleProperty($roles);
    }

    /**
     * buildValidUserRoleProperty
     *
     * @param User  $user  user
     * @param array $roles roles
     *
     * @return UserRoleProperty
     */
    public function buildValidUserRoleProperty(
        User $user,
        $roles = []
    ) {
        return $this->getUserRoleService()->buildValidUserRoleProperty(
            $user,
            $roles
        );
    }

    /**
     * buildValidRoles
     *
     * @param User  $user  user
     * @param array $roles roles
     *
     * @return array
     */
    public function buildValidRoles(
        User $user,
        $roles = []
    ) {
        return $this->getUserRoleService()->buildValidRoles(
            $user,
            $roles
        );
    }
}
