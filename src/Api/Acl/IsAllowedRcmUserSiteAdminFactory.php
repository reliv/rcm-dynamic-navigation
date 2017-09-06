<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\Api\GetSiteByRequest;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserSiteAdminFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedRcmUserSiteAdmin
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedRcmUserSiteAdmin(
            $serviceContainer->get(RcmUserService::class),
            $serviceContainer->get(GetSiteByRequest::class),
            $serviceContainer->get(ResourceName::class)
        );
    }
}
