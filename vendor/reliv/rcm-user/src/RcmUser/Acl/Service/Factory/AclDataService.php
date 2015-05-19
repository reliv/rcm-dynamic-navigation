<?php
/**
 * AclDataService.php
 *
 * AclDataService
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

namespace RcmUser\Acl\Service\Factory;

use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AclDataService
 *
 * AclDataService Factory
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
class AclDataService implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return mixed|\RcmUser\Config\Config
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $aclRoleDataMapper = $serviceLocator->get(
            'RcmUser\Acl\AclRoleDataMapper'
        );
        $aclRuleDataMapper = $serviceLocator->get(
            'RcmUser\Acl\AclRuleDataMapper'
        );

        $service = new \RcmUser\Acl\Service\AclDataService();
        $service->setAclRoleDataMapper($aclRoleDataMapper);
        $service->setAclRuleDataMapper($aclRuleDataMapper);

        return $service;
    }
}
