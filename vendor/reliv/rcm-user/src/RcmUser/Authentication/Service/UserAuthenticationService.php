<?php
/**
 * UserAuthenticationService.php
 *
 * AUTHENTICATION events which trigger the listeners which do the actual work
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service;
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Authentication\Service;

use
    RcmUser\Event\EventProvider;
use
    RcmUser\User\Entity\User;
use
    Zend\Authentication\Result;

/**
 * UserAuthenticationService
 *
 * AUTHENTICATION events which trigger the listeners which do the actual work
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserAuthenticationService extends EventProvider
{

    /**
     * @var bool
     * Force returned user to hide password,
     * can cause issues is return object is meant to be saved.
     */
    protected $obfuscatePassword = true;

    /**
     * setObfuscatePassword
     *
     * @param bool $obfuscatePassword obfuscatePassword
     *
     * @return void
     */
    public function setObfuscatePassword($obfuscatePassword)
    {
        $this->obfuscatePassword = $obfuscatePassword;
    }

    /**
     * getObfuscatePassword
     *
     * @return bool
     */
    public function getObfuscatePassword()
    {
        return $this->obfuscatePassword;
    }

    /**
     * validateCredentials
     *
     * @param User $user user
     *
     * @return mixed
     * @throws \Exception
     */
    public function validateCredentials(User $user)
    {
        /* + LOW_LEVEL_PREP */
        if (!$user->isEnabled()) {
            return new Result(Result::FAILURE_UNCATEGORIZED, $user, ['User is disabled.']);
        }
        /* - LOW_LEVEL_PREP */

        /* @event validateCredentials
         * - expects listener to return
         * Zend\Authentication\Result with
         * Result->identity == to user object ($user)
         */
        $results = $this->getEventManager()->trigger(
            'validateCredentials',
            $this,
            ['user' => $user],
            function ($result) {
                return $result->isValid();
            }
        );

        $result = $results->last();

        if ($result === null) {
            throw new \Exception('No auth listener registered or no results returned.');
        }

        if ($results->stopped()) {

            /*
             * @event validateCredentialsSuccess
             */
            $this->getEventManager()->trigger(
                'validateCredentialsSuccess',
                $this,
                ['result' => $result]
            );

            return new Result(Result::SUCCESS, $this->prepareUser(
                $result->getIdentity()
            ));
        }

        /*
         * @event validateCredentialsFail
         */
        $this->getEventManager()->trigger(
            'validateCredentialsFail',
            $this,
            ['result' => $result]
        );

        return $result;
    }

    /**
     * authenticate
     *
     * @param User $user user
     *
     * @return mixed
     * @throws \Exception
     */
    public function authenticate(User $user)
    {
        /* + LOW_LEVEL_PREP */
        if (!$user->isEnabled()) {
            return new Result(Result::FAILURE_UNCATEGORIZED, $user, ['User is disabled.']);
        }
        /* - LOW_LEVEL_PREP */

        /* @event authenticate
         * - expects listener to return
         * Zend\Authentication\Result with
         * Result->identity == to user object ($user)
         */
        $results = $this->getEventManager()->trigger(
            'authenticate',
            $this,
            ['user' => $user],
            function ($result) {
                return $result->isValid();
            }
        );

        $result = $results->last();

        if ($result === null) {
            throw new \Exception('No auth listener registered or no results returned.');
        }

        if ($results->stopped()) {

            /*
             * @event authenticateSuccess
             */
            $this->getEventManager()->trigger(
                'authenticateSuccess',
                $this,
                ['result' => $result]
            );

            return new Result(Result::SUCCESS, $this->prepareUser(
                $result->getIdentity()
            ));
        }

        /*
         * @event authenticateFail
         */
        $this->getEventManager()->trigger(
            'authenticateFail',
            $this,
            ['result' => $result]
        );

        return $result;
    }

    /**
     * clearIdentity
     *
     * @return void
     */
    public function clearIdentity()
    {
        /*
         * @event clearIdentity
         */
        $this->getEventManager()->trigger(
            'clearIdentity',
            $this,
            []
        );
    }

    /**
     * hasIdentity
     *
     * @return bool
     */
    public function hasIdentity()
    {
        $results = $this->getEventManager()->trigger(
            'hasIdentity',
            $this,
            [],
            function ($hasIdentity) {
                return $hasIdentity;
            }
        );

        $hasIdentity = $results->last();

        return $hasIdentity;
    }

    /**
     * setIdentity - used to refresh session user
     *
     * @param User $identity identity
     *
     * @return void
     */
    public function setIdentity(User $identity)
    {
        /*
         * @event setIdentity
         */
        $this->getEventManager()->trigger(
            'setIdentity',
            $this,
            ['identity' => $identity]
        );
    }

    /**
     * getIdentity
     *
     * @param null $deflt default is no identity
     *
     * @return mixed|null
     */
    public function getIdentity($deflt = null)
    {
        /*
         * @event getIdentity
         */
        $results = $this->getEventManager()->trigger(
            'getIdentity',
            $this,
            []
        );

        $user = $results->last();

        if (empty($user)) {
            return $deflt;
        }

        return $this->prepareUser($user);
    }

    /**
     * prepareUser
     *
     * @param User $user user
     *
     * @return User
     */
    public function prepareUser(User $user)
    {

        if ($this->getObfuscatePassword()) {

            $user->setPassword(User::PASSWORD_OBFUSCATE);
        }

        return $user;
    }
}
