<?php
/**
 * UserValidator.php
 *
 * UserValidator
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
    RcmUser\User\InputFilter\UserInputFilter;
use
    Zend\InputFilter\Factory;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserValidator
 *
 * UserValidator
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
class UserValidator implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return UserValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('RcmUser\User\Config')->get(
            'InputFilter',
            []
        );
        $userInputFilter = new UserInputFilter();
        $factory = new Factory();

        $service
            = new \RcmUser\User\Data\UserValidator($factory, $userInputFilter, $config);

        return $service;
    }
}
