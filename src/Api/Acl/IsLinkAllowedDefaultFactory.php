<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use RcmDynamicNavigation\Api\GetIsAllowedServiceConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsLinkAllowedDefaultFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsLinkAllowedDefault
     */
    public function __invoke($serviceContainer)
    {
        return new IsLinkAllowedDefault(
            $serviceContainer,
            $serviceContainer->get(GetIsAllowedServiceConfig::class)
        );
    }
}
