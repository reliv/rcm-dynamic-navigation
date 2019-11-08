<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserRolesFactory
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
        return new IsAllowedRcmUserRoles(
            $serviceContainer->get(IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance::class)
        );
    }
}
