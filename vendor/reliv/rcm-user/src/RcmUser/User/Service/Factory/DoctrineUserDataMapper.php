<?php
/**
 * DoctrineUserDataMapper.php
 *
 * DoctrineUserDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Service\Factory
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
 * Class DoctrineUserDataMapper
 *
 * DoctrineUserDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DoctrineUserDataMapper implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return DoctrineUserDataMapper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $udp = $serviceLocator->get('RcmUser\User\Data\UserDataPreparer');
        $udv = $serviceLocator->get('RcmUser\User\Data\UserValidator');

        $service = new \RcmUser\User\Db\DoctrineUserDataMapper();
        $service->setEntityManager($em);
        $service->setEntityClass(
            'RcmUser\User\Entity\DoctrineUser'
        );
        $service->setUserDataPreparer($udp);
        $service->setUserValidator($udv);

        return $service;
    }
}
