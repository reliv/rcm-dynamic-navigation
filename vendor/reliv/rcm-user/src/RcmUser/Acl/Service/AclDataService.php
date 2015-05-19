<?php
/**
 * AclDataService.php
 *
 * AclDataService
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

use
    RcmUser\Acl\Db\AclRoleDataMapperInterface;
use
    RcmUser\Acl\Db\AclRuleDataMapperInterface;
use
    RcmUser\Acl\Entity\AclRole;
use
    RcmUser\Acl\Entity\AclRule;
use RcmUser\Acl\Entity\NamespaceAclRole;
use
    RcmUser\Result;

/**
 * AclDataService
 *
 * AclDataService @todo COMPLETE ME
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
class AclDataService
{
    /**
     * @var AclRoleDataMapperInterface
     */
    protected $aclRoleDataMapper;

    /**
     * @var AclRuleDataMapperInterface
     */
    protected $aclRuleDataMapper;

    /**
     * setAclRoleDataMapper
     *
     * @param AclRoleDataMapperInterface $aclRoleDataMapper aclRoleDataMapper
     *
     * @return void
     */
    public function setAclRoleDataMapper(
        AclRoleDataMapperInterface $aclRoleDataMapper
    ) {
        $this->aclRoleDataMapper = $aclRoleDataMapper;
    }

    /**
     * getAclRoleDataMapper
     *
     * @return AclRoleDataMapperInterface
     */
    public function getAclRoleDataMapper()
    {
        return $this->aclRoleDataMapper;
    }

    /**
     * setAclRuleDataMapper
     *
     * @param AclRuleDataMapperInterface $aclRuleDataMapper aclRuleDataMapper
     *
     * @return void
     */
    public function setAclRuleDataMapper(
        AclRuleDataMapperInterface $aclRuleDataMapper
    ) {
        $this->aclRuleDataMapper = $aclRuleDataMapper;
    }

    /**
     * getAclRuleDataMapper
     *
     * @return AclRuleDataMapperInterface
     */
    public function getAclRuleDataMapper()
    {
        return $this->aclRuleDataMapper;
    }

    /* ROLES ******************** */

    /**
     * getAllRolesData - alias getAllRoles without result
     *
     * @return array
     */
    public function getAllRolesData()
    {
        $result = $this->getAllRoles();

        return $result->getData();
    }

    /**
     * getDefaultGuestRoleIds
     *
     * @return Result
     */
    public function getDefaultGuestRoleIds()
    {
        return $this->aclRoleDataMapper->fetchDefaultGuestRoleIds();
    }

    /**
     * getDefaultUserRoleIds
     *
     * @return Result
     */
    public function getDefaultUserRoleIds()
    {
        return $this->aclRoleDataMapper->fetchDefaultUserRoleIds();
    }

    /**
     * getSuperAdminRoleId
     *
     * @return Result
     */
    public function getSuperAdminRoleId()
    {
        return $this->aclRoleDataMapper->fetchSuperAdminRoleId();
    }

    /**
     * getGuestRoleId
     *
     * @return Result
     */
    public function getGuestRoleId()
    {
        return $this->aclRoleDataMapper->fetchGuestRoleId();
    }

    /**
     * getAllRoles
     *
     * @return Result
     */
    public function getAllRoles()
    {
        return $this->aclRoleDataMapper->fetchAll();
    }

    /**
     * getAllRoles
     *
     * @return Result containing array of AcLRoles
     */
    public function getRoleLineage($roleId)
    {
        return $this->aclRoleDataMapper->fetchRoleLineage($roleId);
    }

    /**
     * getRoleByRoleId
     *
     * @param string $roleId roleId
     *
     * @return mixed
     */
    public function getRoleByRoleId($roleId)
    {
        return $this->aclRoleDataMapper->fetchByRoleId($roleId);
    }

    /**
     * createRole
     *
     * @param AclRole $aclRole aclRole
     *
     * @return Result
     */
    public function createRole(AclRole $aclRole)
    {
        return $this->aclRoleDataMapper->create($aclRole);
    }

    /**
     * readRole
     *
     * @param AclRole $aclRole aclRole
     *
     * @return Result
     */
    public function readRole(AclRole $aclRole)
    {
        return $this->aclRoleDataMapper->read($aclRole);
    }

    /**
     * deleteRole
     *
     * @param AclRole $aclRole aclRole
     *
     * @return Result
     */
    public function deleteRole(AclRole $aclRole)
    {
        $roleId = $aclRole->getRoleId();

        // some roles should not be deleted, like super admin and guest
        $superAdminRoleId = $this->getSuperAdminRoleId()->getData();
        if ($roleId == $superAdminRoleId) {
            return new Result(null, Result::CODE_FAIL, "Super admin role ({$roleId}) cannot be deleted.");
        }

        $guestRoleId = $this->getGuestRoleId()->getData();
        if ($roleId == $guestRoleId) {
            return new Result(null, Result::CODE_FAIL, "Guest role ({$roleId}) cannot be deleted.");
        }

        $result = $this->aclRoleDataMapper->delete($aclRole);

        if (!$result->isSuccess()) {
            return $result;
        }

        $rulesResult = $this->getRulesByRole($roleId);

        if (!$rulesResult->isSuccess()) {

            $rulesResult->setMessage(
                'Could not remove related rules for role: ' . $roleId
            );

            return $rulesResult;
        }

        $aclRules = $rulesResult->getData();

        foreach ($aclRules as $aclRule) {

            $ruleResult = $this->deleteRule($aclRule);
            if (!$ruleResult->isSuccess()) {

                $result->setCode(Result::CODE_FAIL);
                $result->setMessage($ruleResult->getMessage());
            }
        }

        return $result;
    }

    /**
     * getRolesWithNamespace
     *
     * @param string $nsChar  nsChar
     * @param bool   $refresh refresh
     *
     * @return array
     */
    public function getRolesWithNamespace(
        $nsChar = '.',
        $refresh = false
    ) {
        $aclRoles = array();
        $roles = $this->getNamespacedRoles(
            $nsChar,
            $refresh
        )->getData();

        $index = 0;
        foreach ($roles as $ns => $nsRole) {

            $aclRoles[$index] = $nsRole;
            $index ++;
        }

        return new Result($aclRoles, Result::CODE_SUCCESS);
    }

    /**
     * getNamespacedRoles
     *
     * @param string $nsChar nsChar
     *
     * @return array
     */
    public function getNamespacedRoles($nsChar = '.')
    {
        $aclRoles = array();
        $result = $this->getAllRoles();

        if (!$result->isSuccess()) {
            return $result;
        }

        $roles = $result->getData();

        foreach ($roles as $role) {

            $ns = $this->createRoleNamespaceId(
                $role,
                $roles,
                $nsChar
            );

            $nsRole = new NamespaceAclRole();

            $nsRole->populate($role);

            $nsRole->setNamespace($ns);

            $aclRoles[$ns] = $nsRole;
        }

        ksort($aclRoles);

        return new Result($aclRoles, Result::CODE_SUCCESS);
    }

    /**
     * createRoleNamespaceId
     *
     * @param AclRole $aclRole  aclRole
     * @param array   $aclRoles aclRoles
     * @param string  $nsChar   nsChar
     *
     * @return string
     */
    public function createRoleNamespaceId(
        AclRole $aclRole,
        $aclRoles,
        $nsChar = '.'
    ) {
        $parentId = $aclRole->getParentRoleId();
        $ns = $aclRole->getRoleId();
        if (!empty($parentId)) {

            $parent = $aclRoles[$parentId];

            $newns = $this->createRoleNamespaceId(
                $parent,
                $aclRoles,
                $nsChar
            );
            $ns = $newns . $nsChar . $ns;
        }

        return $ns;
    }

    /* RULES ******************** */

    /**
     * getAllRules
     *
     * @return Result
     */
    public function getAllRules()
    {
        return $this->aclRuleDataMapper->fetchAll();
    }

    /**
     * getRulesByResources
     *
     * @param array $resources Array of Resources to lookup
     *
     * @return Result
     */
    public function getRulesByResources(Array $resources)
    {
        return $this->aclRuleDataMapper->fetchByResources($resources);
    }

    /**
     * getRulesByResource
     *
     * @param string $resourceId $resourceId
     *
     * @return Result
     */
    public function getRulesByResource($resourceId)
    {
        return $this->aclRuleDataMapper->fetchByResource($resourceId);
    }

    /**
     * getRulesByResourcePrivilege
     *
     * @param $resourceId
     * @param $privilege
     *
     * @return \RcmUser\Acl\Db\Result
     */
    public function getRulesByResourcePrivilege($resourceId, $privilege)
    {
        return $this->aclRuleDataMapper->fetchByResourcePrivilege(
            $resourceId,
            $privilege
        );
    }

    /**
     * getRulesByRole
     *
     * @param string $roleId roleId
     *
     * @return Result
     */
    public function getRulesByRole($roleId)
    {
        return $this->aclRuleDataMapper->fetchByRole($roleId);
    }

    /**
     * createRule
     *
     * @param AclRule $aclRule aclRule
     *
     * @return Result
     */
    public function createRule(AclRule $aclRule)
    {
        $rule = $aclRule->getRule();
        $roleId = $aclRule->getRoleId();
        $resource = $aclRule->getResourceId();

        // check required
        if (empty($rule) || empty($roleId) || empty($resource)) {
            return new Result(
                null,
                Result::CODE_FAIL,
                "New rule requires: rule, roleId and resourceId."
            );
        }

        // check if is super admin
        if ($roleId == $this->getSuperAdminRoleId()->getData()) {
            return new Result(
                null,
                Result::CODE_FAIL,
                "Rules cannot be assigned to super admin."
            );
        }

        // check if role exists
        $result = $this->getRoleByRoleId($roleId);

        if (!$result->isSuccess()) {
            return new Result(
                null,
                Result::CODE_FAIL,
                "Rules cannot be assigned to role that does not exist."
            );
        }

        // @todo validate resource/privilege exists
        return $this->aclRuleDataMapper->create($aclRule);
    }

    /**
     * createRule
     *
     * @param AclRule $aclRule aclRule
     *
     * @return Result
     */
    public function deleteRule(AclRule $aclRule)
    {
        $rule = $aclRule->getRule();
        $roleId = $aclRule->getRoleId();
        $resource = $aclRule->getResourceId();
        //$privilege = $aclRule->getPrivilege();

        // check required
        if (empty($rule) || empty($roleId) || empty($resource)) {
            return new Result(
                null,
                Result::CODE_FAIL,
                "Rule requires: rule, roleId and resourceId."
            );
        }

        // check if exists and get valid id
        $result = $this->aclRuleDataMapper->read($aclRule);

        if (!$result->isSuccess()) {
            return $result;
        }

        return $this->aclRuleDataMapper->delete($result->getData());
    }

    /**
     * getRulesByRoles
     *
     * @param string $nsChar nsChar
     *
     * @return array
     */
    public function getRulesByRoles($nsChar = '.')
    {
        $aclRoles = array();
        $result = $this->getNamespacedRoles($nsChar);

        if (!$result->isSuccess()) {
            return $result;
        }

        $roles = $result->getData();

        foreach ($roles as $ns => $role) {

            $id = $role->getRoleId();
            $aclRoles[$ns] = array();
            $aclRoles[$ns]['role'] = $role;
            $aclRoles[$ns]['roleNs'] = $ns;
            $rulesResult = $this->getRulesByRole($id);
            if ($rulesResult->isSuccess()) {

                $aclRoles[$ns]['rules'] = $rulesResult->getData();
            } else {

                $aclRoles[$ns]['rules'] = array();
            }
        }

        return new Result($aclRoles, Result::CODE_SUCCESS);
    }
}
