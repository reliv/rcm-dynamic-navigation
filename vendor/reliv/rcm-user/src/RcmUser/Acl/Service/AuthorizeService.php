<?php
/**
 * AuthorizeService.php
 *
 * AuthorizeService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Acl\Service;

use RcmUser\Acl\Entity\AclRole;
use
    RcmUser\Acl\Entity\AclRule;
use
    RcmUser\Exception\RcmUserException;
use
    RcmUser\User\Entity\User;
use
    RcmUser\User\Entity\UserRoleProperty;
use
    Zend\Permissions\Acl\Acl;

/**
 * Class AuthorizeService
 *
 * AuthorizeService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AuthorizeService
{
    /**
     *
     * @var string RESOURCE_DELIMITER
     */
    const RESOURCE_DELIMITER = '.';

    /**
     * @var Acl $acl
     */
    protected $acl;

    /**
     * @var AclResourceService $aclResourceService
     */
    protected $aclResourceService;

    /**
     * @var AclDataService $aclDataService
     */
    protected $aclDataService;

    /**
     * __construct
     *
     * @param AclResourceService $aclResourceService aclResourceService
     * @param AclDataService     $aclDataService     aclDataService
     */
    public function __construct(
        AclResourceService $aclResourceService,
        AclDataService $aclDataService
    ) {
        $this->aclResourceService = $aclResourceService;
        $this->aclDataService = $aclDataService;
    }

    /**
     * getAclResourceService
     *
     * @return AclResourceService
     */
    public function getAclResourceService()
    {
        return $this->aclResourceService;
    }

    /**
     * getAclResourceService
     *
     * @return AclDataService
     */
    public function getAclDataService()
    {
        return $this->aclDataService;
    }

    /**
     * getSuperAdminRoleId
     *
     * @return string
     */
    public function getSuperAdminRoleId()
    {
        return $this->getAclDataService()->getSuperAdminRoleId();
    }

    /**
     * Get the guest user role id
     *
     * @return string
     */
    public function getGuestRole()
    {
        $id = $this->getAclDataService()->getGuestRoleId()->getData();

        return [$this->getAclDataService()->getRoleByRoleId($id)->getData()];
    }

    /**
     * getRoles
     *
     * @return array
     */
    public function getRoles()
    {
        $result = $this->getAclDataService()->getNamespacedRoles();

        if (!$result->isSuccess()) {

            // @todo Throw error?
            return [];
        }

        return $result->getData();
    }

    /**
     * getUserRoles
     *
     * @param User|null $user user
     *
     * @return null
     */
    public function getUserRoles($user)
    {
        if (!($user instanceof User)) {

            return $this->getGuestRole();
        }

        /** @var $userRoleProperty UserRoleProperty */
        $userRoleProperty = $user->getProperty(UserRoleProperty::PROPERTY_KEY);

        if (!($userRoleProperty instanceof UserRoleProperty)) {
            return [];
        }

        return $userRoleProperty->getRoles();
    }

    /**
     * getRules
     *
     * @param array $resources resources
     *
     * @return array
     */
    public function getRules($resources = null)
    {
        if (empty($resources)) {
            $result = $this->getAclDataService()->getAllRules();

            if (!$result->isSuccess()) {

                // @todo Throw error?
                return [];
            }

            return $result->getData();
        }

        $rules = $this->getAclDataService()->getRulesByResources($resources);

        return $rules->getData();
    }

    /**
     * getResources
     *
     * @param string $resourceId resourceId
     * @param string $providerId providerId
     *
     * @return array
     */
    public function getResources(
        $resourceId,
        $providerId = null
    ) {
        return $this->getAclResourceService()->getResources(
            $resourceId,
            $providerId
        );
    }

    /**
     * getAcl - This cannot be called before resources are parsed
     *
     * @param string $resourceId resourceId
     * @param string $providerId providerId
     *
     * @return Acl
     */
    public function getAcl(
        $resourceId,
        $providerId
    ) {
        if (!isset($this->acl)) {

            $this->buildAcl();
        }

        /* resources privileges
            we load the every time so they maybe updated dynamically
        */
        $resources = $this->getResources(
            $resourceId,
            $providerId
        );

        foreach ($resources as $resource) {

            if (!$this->acl->hasResource($resource)) {

                $this->acl->addResource(
                    $resource,
                    $resource->getParentResource()
                );
            }

            $privileges = $resource->getPrivileges();

            if (!empty($privileges)) {

                foreach ($privileges as $privilege) {
                    if (!$this->acl->hasResource($privilege)) {

                        $this->acl->addResource(
                            $privilege,
                            $resource
                        );
                    }
                }
            }
        }

        // get only for resources
        $rules = $this->getRules($resources);

        foreach ($rules as $aclRule) {

            if ($aclRule->getRule() == AclRule::RULE_ALLOW) {

                $this->acl->allow(
                    $aclRule->getRoleId(),
                    $aclRule->getResourceId(),
                    $aclRule->getPrivilege(),
                    $aclRule->getAssertion()
                );
            } elseif ($aclRule->getRule() == AclRule::RULE_DENY) {

                $this->acl->deny(
                    $aclRule->getRoleId(),
                    $aclRule->getResourceId(),
                    $aclRule->getPrivilege(),
                    $aclRule->getAssertion()
                );
            }
        }

        return $this->acl;
    }

    /**
     * buildAcl
     *
     * @return void
     */
    public function buildAcl()
    {
        $this->acl = new Acl();

        // roles
        $roles = $this->getRoles();

        foreach ($roles as $role) {

            if ($this->acl->hasRole($role)) {
                // @todo throw error?
                continue;
            }

            $this->acl->addRole(
                $role,
                $role->getParent()
            );
        }
    }

    public function hasSuperAdmin($userRoles)
    {
        $superAdminRoleId = $this->getSuperAdminRoleId()->getData();

        if (!empty($superAdminRoleId)
            && is_array($userRoles)
            && in_array(
                $superAdminRoleId,
                $userRoles
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * isAllowed
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     * @param string $providerId providerId
     * @param User   $user       user
     *
     * @return bool
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function isAllowed(
        $resourceId,
        $privilege = null,
        $providerId = null,
        $user = null
    ) {
        $resourceId = strtolower($resourceId);

        /* Get roles or guest roles if no user */
        $userRoles = $this->getUserRoles($user);

        /* Check super admin
            we over-ride everything if user has super admin
        */
        if($this->hasSuperAdmin($userRoles)){
            return true;
        }

        try {
            $acl = $this->getAcl(
                $resourceId,
                $providerId
            );

            foreach ($userRoles as $userRole) {

                // @todo this will fail on deny
                $result = $acl->isAllowed(
                    $userRole,
                    $resourceId,
                    $privilege
                );

                if ($result) {
                    return $result;
                }
            }

        } catch (\Exception $e) {
            // @todo - report this error or log
            $message = '<pre>';
            $message .= "AuthorizeService->isAllowed failed to check: \n" .
                "providerId: {$providerId} \n" .
                "resourceId: {$resourceId} \n" .
                'privilege: ' . var_export($privilege, true) . " \n";
            if ($user) {
                $message .= 'user id: ' . $user->getId() . " \n";
            }
            $message .= 'user roles: ' . var_export($userRoles, true) . " \n";
            $message .= 'acl roles: ' . var_export(
                $this->getAcl($resourceId, $providerId)->getRoles(), true
            ) . " \n";
            $message .='defined roles: ' . var_export($this->getRoles() , true) . " \n";
            $message .= 'Acl failed with error: ' . $e->getMessage();
            $message .= '</pre>';
            //echo($message);
            throw new RcmUserException($message, 401);
        }

        return false;

        /* @deprecated
        $resources = $this->parseResource($resourceId);
         *
         * foreach ($resources as $res) {
         *
         * $acl = $this->getAcl($resourceId, $providerId);
         *
         * foreach ($userRoles as $userRole) {
         *
         * $result = $acl->isAllowed(
         * $userRole,
         * $res,
         * $privilege
         * );
         *
         * if ($result) {
         * return $result;
         * }
         * }
         * }
         */
    }

    /**
     * hasRoleBasedAccess
     *
     * @param User $user
     * @param      $roleId
     *
     * @return bool
     */
    public function hasRoleBasedAccess(User $user, $roleId)
    {
        /* Get roles or guest roles if no user */
        $userRoles = $this->getUserRoles($user);

        /* Check super admin
            we over-ride everything if user has super admin
        */
        if($this->hasSuperAdmin($userRoles)){
            return true;
        }

        foreach($userRoles as $userRole){

            if($userRole instanceof AclRole){
                $userRoleId = $userRole->getRoleId();
            } else {
                $userRoleId = $userRole;
            }

            $result = $this->aclDataService->getRoleLineage($userRoleId);

            $checkRoles = $result->getData();

            if(array_key_exists($roleId, $checkRoles)){
                return true;
            }
        }

        return false;
    }

    /**
     * parseResource @deprecated
     *
     * This allows use to parse our dot notation for nested resources
     * which is used when a missing resource can inherit.
     *
     * To do this we need to provide the resource and its parent.
     * We accomplish this by passing 'PAGES.PAGE_X'.
     * Our isAllowed override allows the checking of 'PAGE_X' first and
     * if it is not found, we check 'PAGES'.
     *
     * Example:
     *  If a resource called 'PAGES'
     *  And we want to check if the user has access
     * to a child of 'PAGES' named 'PAGE_X'.
     *  And we know at the time of the ACL check
     * that 'PAGE_X' might not be defined.
     *  If 'PAGE_X' is not defined, then we inherit from from 'PAGES'
     *
     * @param string $resource resource
     *
     * @return array
     */
    public function parseResource($resource)
    {
        if (is_string($resource)) {

            $resources = explode(
                self::RESOURCE_DELIMITER,
                $resource
            );

            $resources = array_reverse($resources);

            return $resources;
        }

        return [$resource];
    }
}
