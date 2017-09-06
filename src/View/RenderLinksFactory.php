<?php

namespace RcmDynamicNavigation\View;

use Psr\Container\ContainerInterface;
use Zend\View\HelperPluginManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RenderLinks
     */
    public function __invoke($serviceContainer)
    {
        // @BC for ZendFramework
        if ($serviceContainer instanceof HelperPluginManager) {
            $serviceContainer = $serviceContainer->getServiceLocator();
        }

        return new RenderLinks(
            $serviceContainer->get(\RcmDynamicNavigation\Api\Render\RenderLinks::class)
        );
    }
}
