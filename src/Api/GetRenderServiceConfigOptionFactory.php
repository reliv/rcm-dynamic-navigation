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
        $config = $serviceContainer->get('config');
        $servicesConfig = $config['rcmPlugin']['RcmDynamicNavigation']['renderServices'];

        return new GetRenderServiceConfigOption(
            $servicesConfig
        );
    }
}
