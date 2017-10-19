<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserRoles implements IsAllowedRoles
{
    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        RcmUserService $rcmUserService
    ) {
        $this->rcmUserService = $rcmUserService;
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
        $permittedRoles = [];
        if (array_key_exists(IsAllowedRoles::OPTION_PERMITTED_ROLES, $options)) {
            $permittedRoles = $options[IsAllowedRoles::OPTION_PERMITTED_ROLES];
        }

        if (empty($permittedRoles)) {
            return true;
        }

        foreach ($permittedRoles as $role) {
            if ($this->rcmUserService->hasRoleBasedAccess($role)) {
                return true;
            }
        }

        return false;
    }
}
