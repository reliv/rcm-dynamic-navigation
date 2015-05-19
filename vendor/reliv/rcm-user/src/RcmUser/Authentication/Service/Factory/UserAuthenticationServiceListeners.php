<?php
/**
 * UserAuthenticationServiceListeners.php
 *
 * UserAuthenticationServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Authentication\Service\Factory;

use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * UserAuthenticationServiceListeners
 *
 * UserAuthenticationServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserAuthenticationServiceListeners implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return array|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $auth = $serviceLocator->get(
            'RcmUser\Authentication\AuthenticationService'
        );

        $service
            = new \RcmUser\Authentication\Event\UserAuthenticationServiceListeners();
        $service->setAuthService($auth);

        return $service;
    }
}
