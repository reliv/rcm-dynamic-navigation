<?php

namespace RcmDynamicNavigation\Api\Acl;

use http\Env\Request;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\GetGroupNamesByUserInterface;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\RequestContext\RequestContext;
use RcmDynamicNavigation\Api\Options;
use RcmUser\Api\Authentication\GetIdentity;

class IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance implements IsAllowedRoles
{
    protected $getIdentity;
    protected $requestContext;
    protected $getGroupNamesByUser;

    public function __construct(
        GetIdentity $getIdentity,
        ContainerInterface $requestContext,
        GetGroupNamesByUserInterface $getGroupNamesByUser
    ) {
        $this->getIdentity = $getIdentity;
        $this->requestContext = $requestContext;
        $this->getGroupNamesByUser = $getGroupNamesByUser;
    }

    /**
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

        $currentUser = $this->getIdentity->__invoke($request);
        $currentUserGroups = $this->getGroupNamesByUser->__invoke($currentUser);
        foreach ($readAccessGroups as $readAccessGroup) {
            if (in_array($readAccessGroup, $currentUserGroups)) {
                return true;//If they have RBAC access, let them see it.
                break;
            }
        }

        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->requestContext->get(AssertIsAllowed::class);

        try {
            $assertIsAllowed->__invoke(AclActions::READ, ['type' => SecurityPropertyConstants::TYPE_ADMIN_TOOL]);

            return true; //If they don't have RBAC access but still have admin tools access, let them see it
        } catch (NotAllowedException $e) {
            return false;
        }
    }
}
