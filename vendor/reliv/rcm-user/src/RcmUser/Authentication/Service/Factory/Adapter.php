<?php
/**
 * Adapter.php
 *
 * Adapter
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
    RcmUser\Authentication\Adapter\UserAdapter;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Adapter
 *
 * Adapter
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
class Adapter implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return mixed|UserAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userDataService = $serviceLocator->get(
            'RcmUser\User\Service\UserDataService'
        );
        $encrypt = $serviceLocator->get('RcmUser\User\Encryptor');
        $adapter = new UserAdapter();
        $adapter->setUserDataService($userDataService);
        $adapter->setEncryptor($encrypt);

        return $adapter;
    }
}
