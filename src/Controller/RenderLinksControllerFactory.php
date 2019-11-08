<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Container\ContainerInterface;
use Rcm\RequestContext\RequestContext;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\Render\RenderLinks;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksControllerFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RenderLinksController
     */
    public function __invoke($serviceContainer)
    {
        return new RenderLinksController(
            $serviceContainer->get(RequestContext::class),
            $serviceContainer->get(RenderLinks::class)
        );
    }
}
