<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Container\ContainerInterface;
use RcmDynamicNavigation\Api\GetRenderServiceConfigOption;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinkBootstrapFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RenderLinkBootstrap
     */
    public function __invoke($serviceContainer)
    {
        return new RenderLinkBootstrap(
            $serviceContainer->get(GetRenderServiceConfigOption::class)
        );
    }
}
