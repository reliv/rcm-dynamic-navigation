<?php
/**
 * RcmUserAclResourceProvider.php
 *
 * RcmUserAclResourceProvider
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
 * RcmUserAclResourceProvider
 *
 * RcmUserAclResourceProvider
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
class RcmUserAclResourceProvider implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return mixed|\RcmUser\Provider\RcmUserAclResourceProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new \RcmUser\Provider\RcmUserAclResourceProvider();

        return $service;
    }
}
