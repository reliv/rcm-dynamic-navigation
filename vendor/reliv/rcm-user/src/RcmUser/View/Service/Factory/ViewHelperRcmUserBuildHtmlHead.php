<?php
/**
 * ViewHelperRcmUserBuildHtmlHead.php
 *
 * ViewHelperRcmUserBuildHtmlHead
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

namespace RcmUser\View\Service\Factory;

use
    RcmUser\View\Helper\RcmUserBuildHtmlHead;
use
    Zend\ServiceManager\FactoryInterface;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ViewHelperRcmUserBuildHtmlHead
 *
 * ViewHelperRcmUserBuildHtmlHead
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\View\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ViewHelperRcmUserBuildHtmlHead implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return mixed|RcmUserBuildHtmlHead
     */
    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $config = $serviceLocator->get(
            'RcmUser\Config'
        );
        $service = new RcmUserBuildHtmlHead($config['htmlAssets']);

        return $service;
    }
}
