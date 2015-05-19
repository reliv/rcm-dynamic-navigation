<?php
/**
 * UserDataMapper.php
 *
 * UserDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmUser\User\Db;

use
    RcmUser\Exception\RcmUserException;
use
    RcmUser\User\Data\UserDataPreparerInterface;
use
    RcmUser\User\Data\UserValidatorInterface;
use
    RcmUser\User\Entity\User;
use
    RcmUser\User\Result;

/**
 * Class UserDataMapper
 *
 * UserDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserDataMapper implements UserDataMapperInterface
{
    const ID_FIELD = 'id';
    const USERNAME_FIELD = 'username';

    /**
     * @var UserDataPreparerInterface
     */
    protected $userDataPreparer;

    /**
     * @var UserValidatorInterface
     */
    protected $userValidator;

    /**
     * setUserDataPreparer
     *
     * @param UserDataPreparerInterface $userDataPreparer userDataPreparer
     *
     * @return void
     */
    public function setUserDataPreparer(
        UserDataPreparerInterface $userDataPreparer
    ) {
        $this->userDataPreparer = $userDataPreparer;
    }

    /**
     * getUserDataPreparer
     *
     * @return UserDataPreparerInterface
     */
    public function getUserDataPreparer()
    {
        return $this->userDataPreparer;
    }

    /**
     * setUserValidator
     *
     * @param UserValidatorInterface $userValidator userValidator
     *
     * @return void
     */
    public function setUserValidator(UserValidatorInterface $userValidator)
    {
        $this->userValidator = $userValidator;
    }

    /**
     * getUserValidator
     *
     * @return UserValidatorInterface
     */
    public function getUserValidator()
    {
        return $this->userValidator;
    }

    /**
     * fetchAll
     *
     * @param array $options options
     *
     * @return mixed
     * @throws RcmUserException
     */
    public function fetchAll(
        $options = []
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchById
     *
     * @param mixed $id user id
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchById($id)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByUsername
     *
     * @param string $username username
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchByUsername($username)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * create
     *
     * @param User $requestUser  requestUser
     * @param User $responseUser responseUser
     *
     * @return Result
     * @throws RcmUserException
     */
    public function create(
        User $requestUser,
        User $responseUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * read
     *
     * @param User $requestUser  requestUser
     * @param User $responseUser responseUser
     *
     * @return Result
     * @throws RcmUserException
     */
    public function read(
        User $requestUser,
        User $responseUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * update
     *
     * @param User $requestUser  requestUser
     * @param User $responseUser responseUser
     * @param User $existingUser existingUser
     *
     * @return mixed|Result
     * @throws RcmUserException
     */
    public function update(
        User $requestUser,
        User $responseUser,
        User $existingUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * delete
     *
     * @param User $requestUser  requestUser
     * @param User $responseUser responseUser
     *
     * @return mixed|Result
     * @throws RcmUserException
     */
    public function delete(
        User $requestUser,
        User $responseUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * canUpdate
     *
     * @param User $user user
     *
     * @return bool
     */
    public function canUpdate(User $user)
    {
        $id = $user->getId();

        if (empty($id)) {
            return false;
        }

        return true;
    }


    /**
     * canDelete
     *
     * @param User $user user
     *
     * @return bool
     */
    public function canDelete(User $user)
    {
        return $this->canUpdate($user);
    }
}
