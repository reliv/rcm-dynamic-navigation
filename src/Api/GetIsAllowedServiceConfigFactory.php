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
        $config = $serviceContainer->get('config');
        $servicesConfig = $config['rcmPlugin']['RcmDynamicNavigation']['isAllowedServices'];

        return new GetIsAllowedServiceConfig(
            $servicesConfig
        );
    }
}
