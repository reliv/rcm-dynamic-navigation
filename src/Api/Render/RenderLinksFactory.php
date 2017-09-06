<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Container\ContainerInterface;

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
        return new RenderLinks();
    }
}
