<?php
/**
 * DoctrineAclRoleDataMapper.php
 *
 * DoctrineAclRoleDataMapper
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
 * DoctrineAclRoleDataMapper
 *
 * DoctrineAclRoleDataMapper Factory
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
class DoctrineAclRoleDataMapper implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return mixed|\RcmUser\Acl\Db\DoctrineAclRoleDataMapper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('RcmUser\Acl\Config');

        $service = new \RcmUser\Acl\Db\DoctrineAclRoleDataMapper($config);
        $service->setEntityManager($em);
        $service->setEntityClass('RcmUser\Acl\Entity\DoctrineAclRole');

        return $service;
    }
}
