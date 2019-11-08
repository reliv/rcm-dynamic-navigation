<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\Options;

/**
 * @deprecated use IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance instead
 *
 * Class IsAllowedRcmUserRoles
 * @package RcmDynamicNavigation\Api\Acl
 */
class IsAllowedRcmUserRoles implements IsAllowedRoles
{
    protected $isAllowedWithoutInheritance;

    public function __construct(
        IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance $isAllowedWithoutInheritance
    ) {
        $this->isAllowedWithoutInheritance = $isAllowedWithoutInheritance;
    }

    /**
     * @deprecated use IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance instead
     *
     * @param ServerRequestInterface $request
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        $permittedRolesString = Options::get(
            $options,
            IsAllowedRoles::OPTION_PERMITTED_ROLES,
            ''
        );

        /**
         * Note: Yes a comma seperated string is very odd compared to a JSON array
         * but is done so the same old JS editor as "IsAllowedRcmUserRoles" can be used.
         */
        $readAccessGroups = explode(',', $permittedRolesString);

        if (empty($readAccessGroups)) {
            return true;//Apperently if no roles are selected, that means everyone has access
        }

        //BC inheritance code that should be removed eventually. Should not really be in open source.
        if (in_array('distributor', $readAccessGroups)) {
            $readAccessGroups[] = 'employee';
        }
        if (in_array('customer', $readAccessGroups)) {
            $readAccessGroups[] = 'employee';
            $readAccessGroups[] = 'distributor';
        }

        return $this->isAllowedWithoutInheritance->__invoke(
            $request,
            [IsAllowedRoles::OPTION_PERMITTED_ROLES => implode(',', array_unique($readAccessGroups))]
        );
    }
}
