<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\Options;
use RcmUser\Api\Acl\HasRoleBasedAccess;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserRoles implements IsAllowedRoles
{
    /**
     * @var HasRoleBasedAccess
     */
    protected $hasRoleBasedAccess;

    /**
     * @param HasRoleBasedAccess $hasRoleBasedAccess
     */
    public function __construct(
        HasRoleBasedAccess $hasRoleBasedAccess
    ) {
        $this->hasRoleBasedAccess = $hasRoleBasedAccess;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        $permittedRoles = Options::get(
            $options,
            IsAllowedRoles::OPTION_PERMITTED_ROLES,
            []
        );

        if (empty($permittedRoles)) {
            return true;
        }

        foreach ($permittedRoles as $role) {
            if ($this->hasRoleBasedAccess->__invoke($request, $role)) {
                return true;
            }
        }

        return false;
    }
}
