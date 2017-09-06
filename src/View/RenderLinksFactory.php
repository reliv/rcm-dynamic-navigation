<?php

namespace RcmDynamicNavigation\View;

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
        return new RenderLinks(
            $serviceContainer->get(\RcmDynamicNavigation\Api\Render\RenderLinks::class)
        );
    }
}
