<?php
/**
 * Class Encryptor
 *
 * Encryptor
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
    Zend\Crypt\Password\Bcrypt;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Encryptor
 *
 * Encryptor
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
class Encryptor implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return PasswordInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cfg = $serviceLocator->get('RcmUser\User\Config');
        $encryptor = new Bcrypt();
        $encryptor->setCost(
            $cfg->get(
                'Encryptor.passwordCost',
                14
            )
        );

        return $encryptor;
    }
}
