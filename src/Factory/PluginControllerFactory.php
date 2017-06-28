<?php

namespace RcmDynamicNavigation\Factory;

use Interop\Container\ContainerInterface;
use RcmDynamicNavigation\Controller\PluginController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for PluginController
 *
 * Factory for PluginController.
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class PluginControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|ControllerManager
     *
     * @return PluginController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        /** @var \Rcm\Acl\CmsPermissionChecks $cmsPermissionChecks */
        $cmsPermissionChecks = $container->get(
            \Rcm\Acl\CmsPermissionChecks::class
        );

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $container->get(\Rcm\Service\CurrentSite::class);

        $config = $container->get('config');

        return new PluginController(
            $cmsPermissionChecks,
            $currentSite,
            $config
        );
    }
}
