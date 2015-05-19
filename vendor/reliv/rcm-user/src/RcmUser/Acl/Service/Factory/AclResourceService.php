<?php
/**
 * AclResourceService.php
 *
 * AclResourceService
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
    RcmUser\Acl\Entity\AclResource;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AclResourceService
 *
 * AclResourceService Factory
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
class AclResourceService implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return mixed|\RcmUser\Acl\Service\AclResourceService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('RcmUser\Acl\Config');

        $resourceProviders = $config->get(
            'ResourceProviders',
            []
        );

        /* root resource */
        $rootPrivileges = [
            'read',
            'update',
            'create',
            'delete',
            'execute',
        ];

        $rootResource = new AclResource('root', null, $rootPrivileges);
        $rootResource->setName('Root');
        $rootResource->setDescription(
            'This is the lowest level resource.  '
            . 'Access to this will allow access to all resources.'
        );

        $service = new \RcmUser\Acl\Service\AclResourceService($rootResource);
        $service->setResourceProviders($resourceProviders);
        $service->setServiceLocator($serviceLocator);

        return $service;
    }
}
