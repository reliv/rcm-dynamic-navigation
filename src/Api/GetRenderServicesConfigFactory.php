<?php

namespace RcmDynamicNavigation\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRenderServicesConfigFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetRenderServicesConfig
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke($serviceContainer)
    {
        $config = $serviceContainer->get('config');
        $servicesConfig = $config['rcmPlugin']['RcmDynamicNavigation']['renderServices'];

        return new GetRenderServicesConfig(
            $servicesConfig
        );
    }
}
