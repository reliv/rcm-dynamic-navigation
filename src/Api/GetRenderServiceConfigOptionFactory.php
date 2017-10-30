<?php

namespace RcmDynamicNavigation\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRenderServiceConfigOptionFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetRenderServiceConfigOption
     */
    public function __invoke($serviceContainer)
    {
        /** @var GetRenderServicesConfig $getRenderServicesConfig */
        $getRenderServicesConfig = $serviceContainer->get(GetRenderServicesConfig::class);

        return new GetRenderServiceConfigOption(
            $getRenderServicesConfig->__invoke()
        );
    }
}
