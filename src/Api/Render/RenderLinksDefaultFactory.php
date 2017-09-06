<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksDefaultFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RenderLinksDefault
     */
    public function __invoke($serviceContainer)
    {
        return new RenderLinksDefault();
    }
}
