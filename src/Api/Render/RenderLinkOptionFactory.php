<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Container\ContainerInterface;
use RcmDynamicNavigation\Api\GetRenderServiceConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinkOptionFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RenderLinkOption
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke($serviceContainer)
    {
        return new RenderLinkOption(
            $serviceContainer,
            $serviceContainer->get(GetRenderServiceConfig::class)
        );
    }
}
