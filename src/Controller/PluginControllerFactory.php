<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Container\ContainerInterface;
use RcmDynamicNavigation\Api\Acl\IsLinkAllowed;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PluginControllerFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface|ControllerManager $serviceContainer
     *
     * @return PluginController
     */
    public function __invoke($serviceContainer)
    {
        // @BC for ZendFramework
        if ($serviceContainer instanceof ControllerManager) {
            $serviceContainer = $serviceContainer->getServiceLocator();
        }

        $config = $serviceContainer->get('Config');

        return new PluginController(
            $serviceContainer->get(IsLinkAllowed::class),
            $config
        );
    }
}
