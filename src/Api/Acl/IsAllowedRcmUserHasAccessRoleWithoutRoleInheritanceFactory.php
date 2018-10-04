<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use RcmUser\Api\Acl\HasRoleBasedAccess;

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
            $serviceContainer->get(HasRoleBasedAccess::class)
        );
    }
}
