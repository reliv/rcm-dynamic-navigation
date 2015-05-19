<?php
/**
 * UserAdapter.php
 *
 * UserAdapter
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Adapter
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Authentication\Adapter;

use
    RcmUser\User\Entity\User;
use
    RcmUser\User\Service\UserDataService;
use
    Zend\Authentication\Adapter\AbstractAdapter;
use
    Zend\Authentication\Result;
use
    Zend\Crypt\Password\PasswordInterface;

/**
 * UserAdapter
 *
 * UserAdapter
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Adapter
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserAdapter extends AbstractAdapter
{
    /**
     * @var
     */
    protected $userDataService;

    /**
     * @var
     */
    protected $encryptor;

    /**
     * @var
     */
    protected $user;

    /**
     * setUser
     *
     * @param User $user user
     *
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * getUser
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

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
     * setEncryptor
     *
     * @param PasswordInterface $encryptor encryptor
     *
     * @return void
     */
    public function setEncryptor(PasswordInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * getEncryptor
     *
     * @return PasswordInterface
     */
    public function getEncryptor()
    {
        return $this->encryptor;
    }

    /**
     * authenticate: Performs an authentication attempt
     *
     * @return Result
     */
    public function authenticate()
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        $password = $user->getPassword();

        $this->setIdentity($user);
        $this->setCredential($password);

        if ($username === null || $password === null) {
            return new Result(Result::FAILURE_IDENTITY_AMBIGUOUS, null, ['User credentials required.']);
        }

        // We will remove id is set so that we only read from username,
        // this will eliminate an incorrect id/username match in the object
        $user->setId(null);

        $existingUserResult = $this->getUserDataService()->readUser($user);

        if (!$existingUserResult->isSuccess()) {

            // ERROR
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, $existingUserResult->getMessages(
            ));
        }

        $existingUser = $existingUserResult->getUser();
        $existingHash = $existingUser->getPassword();

        $credential = $user->getPassword();

        $isValid = $this->getEncryptor()->verify(
            $credential,
            $existingHash
        );
        if ($isValid) {

            $result = new Result(Result::SUCCESS, $existingUser, []);
        } else {

            $result
                = new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['User credential invalid.']);
        }

        return $result;
    }
}
