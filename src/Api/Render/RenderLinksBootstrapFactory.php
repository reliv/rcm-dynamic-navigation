<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Container\ContainerInterface;
use RcmDynamicNavigation\Api\GetRenderServiceConfigOption;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksBootstrapFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RenderLinksBootstrap
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke($serviceContainer)
    {
        return new RenderLinksBootstrap(
            $serviceContainer->get(GetRenderServiceConfigOption::class),
            $serviceContainer->get(RenderLinkOption::class)
        );
    }
}
