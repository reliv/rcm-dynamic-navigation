<?php
/**
 * RcmUserService.php
 *
 * RcmUserService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Service\Factory;

use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RcmUserService
 *
 * RcmUserService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserService implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return mixed|\RcmUser\Service\RcmUserService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authServ = $serviceLocator->get(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        );
        $userDataService = $serviceLocator->get(
            'RcmUser\User\Service\UserDataService'
        );
        $userPropertyService = $serviceLocator->get(
            'RcmUser\User\Service\UserPropertyService'
        );
        $authorizeService = $serviceLocator->get(
            'RcmUser\Acl\Service\AuthorizeService'
        );

        $service = new \RcmUser\Service\RcmUserService();
        $service->setUserDataService($userDataService);
        $service->setUserPropertyService($userPropertyService);
        $service->setUserAuthService($authServ);
        $service->setAuthorizeService($authorizeService);

        return $service;
    }
}
