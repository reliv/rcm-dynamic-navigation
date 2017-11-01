<?php

namespace RcmDynamicNavigation\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRenderServiceConfigFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetRenderServiceConfig
     */
    public function __invoke($serviceContainer)
    {
        /** @var GetRenderServicesConfig $getRenderServicesConfig */
        $getRenderServicesConfig = $serviceContainer->get(GetRenderServicesConfig::class);

        return new GetRenderServiceConfig(
            $getRenderServicesConfig->__invoke()
        );
    }
}
