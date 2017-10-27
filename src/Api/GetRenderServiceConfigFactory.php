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
        $config = $serviceContainer->get('config');
        $servicesConfig = $config['rcmPlugin']['RcmDynamicNavigation']['renderServices'];

        return new GetRenderServiceConfig(
            $servicesConfig
        );
    }
}
