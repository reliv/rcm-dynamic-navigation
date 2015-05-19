<?php
/**
 * UserRoleDataServiceListeners.php
 *
 * UserRoleDataServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Service\Factory;

use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * UserRoleDataServiceListeners
 *
 * UserRoleDataServiceListeners Factory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserRoleDataServiceListeners implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return array
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new \RcmUser\User\Event\UserRoleDataServiceListeners();

        $service->setUserRoleService(
            $serviceLocator->get('RcmUser\User\Service\UserRoleService')
        );

        return $service;
    }
}
