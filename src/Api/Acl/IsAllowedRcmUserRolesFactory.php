<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use RcmUser\Api\Acl\HasRoleBasedAccess;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserRolesFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedRcmUserRoles
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedRcmUserRoles(
            $serviceContainer->get(HasRoleBasedAccess::class)
        );
    }
}
