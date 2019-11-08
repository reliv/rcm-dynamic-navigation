<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use Rcm\Acl\GetGroupNamesByUserInterface;
use Rcm\RequestContext\RequestContext;
use RcmUser\Api\Authentication\GetIdentity;

class IsAllowedRcmUserHasAccessRoleWithoutRoleInheritanceFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedRcmUserRoles
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(RequestContext::class),
            $serviceContainer->get(GetGroupNamesByUserInterface::class)
        );
    }
}
