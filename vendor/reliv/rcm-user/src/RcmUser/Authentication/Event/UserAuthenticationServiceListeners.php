<?php
/**
 * UserAuthenticationServiceListeners.php
 *
 * UserAuthenticationServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Authentication\Event;

use
    Zend\Authentication\Result;

/**
 * UserAuthenticationServiceListeners
 *
 * UserAuthenticationServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserAuthenticationServiceListeners extends AbstractAuthServiceListeners
{
    /**
     * @var string
     */
    protected $id = 'RcmUser\Authentication\Service\UserAuthenticationService';

    /**
     * @var int
     */
    protected $priority = 1;

    /**
     * @var array
     */
    protected $listenerMethods
        = [
            'onValidateCredentials' => 'validateCredentials',
            //'onValidateCredentialsSuccess' => 'validateCredentialsSuccess',
            //'onValidateCredentialsFail' => 'validateCredentialsFail',

            'onAuthenticate' => 'authenticate',
            //'onAuthenticateSuccess' => 'authenticateSuccess',
            //'onAuthenticateFail' => 'authenticateFail',

            'onClearIdentity' => 'clearIdentity',
            'onHasIdentity' => 'hasIdentity',
            'onSetIdentity' => 'setIdentity',
            'onGetIdentity' => 'getIdentity',
        ];

    /**
     * onValidateCredentials
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onValidateCredentials($e)
    {
        $user = $e->getParam('user');

        // RcmUser Auth
        $adapter = $this->getAuthService()->getAdapter();
        $adapter->setUser($user);
        $result = $adapter->authenticate();

        return $result;
    }

    /**
     * onAuthenticate
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onAuthenticate($e)
    {
        $user = $e->getParam('user');

        // RcmUser Auth
        $adapter = $this->getAuthService()->getAdapter();
        $adapter->setUser($user);
        $result = $this->getAuthService()->authenticate($adapter);

        return $result;
    }

    /**
     * onClearIdentity
     *
     * @param Event $e e
     *
     * @return bool
     */
    public function onClearIdentity($e)
    {
        $authService = $this->getAuthService();

        if ($authService->hasIdentity()) {

            $authService->clearIdentity();
        }
    }

    /**
     * onHasIdentity
     *
     * @param Event $e e
     *
     * @return bool|Result
     */
    public function onHasIdentity($e)
    {
        $authService = $this->getAuthService();

        return $authService->hasIdentity();
    }

    /**
     * onSetIdentity
     *
     * @param Event $e e
     *
     * @return void|Result
     */
    public function onSetIdentity($e)
    {
        $identity = $e->getParam('identity');

        $authService = $this->getAuthService();

        $authService->setIdentity($identity);
    }

    /**
     * onGetIdentity
     *
     * @param Event $e e
     *
     * @return User|null
     */
    public function onGetIdentity($e)
    {
        $authService = $this->getAuthService();

        if ($authService->hasIdentity()) {
            return $authService->getIdentity();
        }

        return null;
    }
}
