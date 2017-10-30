<?php

namespace RcmDynamicNavigation\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetIsAllowedServiceConfigFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetIsAllowedServiceConfig
     */
    public function __invoke($serviceContainer)
    {
        /** @var GetIsAllowedServicesConfig $getIsAllowedServiceConfig */
        $getIsAllowedServiceConfig = $serviceContainer->get(GetIsAllowedServicesConfig::class);

        return new GetIsAllowedServiceConfig(
            $getIsAllowedServiceConfig->__invoke()
        );
    }
}
