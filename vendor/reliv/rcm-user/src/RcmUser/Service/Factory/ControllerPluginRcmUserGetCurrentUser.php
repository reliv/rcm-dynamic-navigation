<?php
/**
 * ControllerPluginRcmUserGetCurrentUser
 *
 * ControllerPluginRcmUserGetCurrentUser
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
    RcmUser\Controller\Plugin\RcmUserGetCurrentUser;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ControllerPluginRcmUserGetCurrentUser
 *
 * ControllerPluginRcmUserGetCurrentUser
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
class ControllerPluginRcmUserGetCurrentUser implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return RcmUserGetCurrentUser
     */
    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $rcmUserService = $serviceLocator->get(
            'RcmUser\Service\RcmUserService'
        );

        $service = new RcmUserGetCurrentUser($rcmUserService);

        return $service;
    }
}
