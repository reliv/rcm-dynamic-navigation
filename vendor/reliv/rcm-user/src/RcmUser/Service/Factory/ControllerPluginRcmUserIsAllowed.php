<?php
/**
 * ControllerPluginRcmUserIsAllowed
 *
 * ControllerPluginRcmUserIsAllowed
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
    RcmUser\Controller\Plugin\RcmUserIsAllowed;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ControllerPluginRcmUserIsAllowed
 *
 * ControllerPluginRcmUserIsAllowed
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
class ControllerPluginRcmUserIsAllowed implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return mixed|RcmUserIsAllowed
     */
    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();

        $authorizeService = $serviceLocator->get(
            'RcmUser\Acl\Service\AuthorizeService'
        );
        $userAuthService = $serviceLocator->get(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        );

        $service = new RcmUserIsAllowed($authorizeService, $userAuthService);

        return $service;
    }
}
