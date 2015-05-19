<?php
/**
 * EventListeners
 *
 * EventListeners
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
 * EventListeners
 *
 * EventListeners
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
class EventListeners implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return array|mixed|object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $listeners = [];

        try {
            $listeners[] = $serviceLocator->get(
                'RcmUser\User\UserDataServiceListeners'
            );
        } catch (\Exception $e) {
            // no listeners
        }

        try {
            $listeners[] = $serviceLocator->get(
                'RcmUser\Authentication\UserAuthenticationServiceListeners'
            );
        } catch (\Exception $e) {
            // no listeners
        }

        try {
            $listeners[] = $serviceLocator->get(
                'RcmUser\User\UserRoleDataServiceListeners'
            );
        } catch (\Exception $e) {
            // no listeners
        }

        return $listeners;
    }
}
