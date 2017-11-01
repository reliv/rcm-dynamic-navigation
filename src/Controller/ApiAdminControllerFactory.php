<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Container\ContainerInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\GetIsAllowedServicesConfig;
use RcmDynamicNavigation\Api\GetRenderServicesConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminControllerFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return ApiAdminController
     */
    public function __invoke($serviceContainer)
    {
        return new ApiAdminController(
            $serviceContainer->get(IsAllowedAdmin::class),
            $serviceContainer->get(GetIsAllowedServicesConfig::class),
            $serviceContainer->get(GetRenderServicesConfig::class)
        );
    }
}
