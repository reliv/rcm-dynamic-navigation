<?php
/**
 * UserRolesDataMapperInterface.php
 *
 * UserRolesDataMapperInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Db;

use
    RcmUser\User\Entity\User;

/**
 * Interface UserRolesDataMapperInterface
 *
 * UserRolesDataMapperInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface UserRolesDataMapperInterface
{

    /**
     * getAvailableRoles
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        {roleId}' => RcmUser\Acl\Entity\AclRole,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @return array
     */
    public function getAvailableRoles();

    /**
     * fetchAll
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RcmUser\User\Entity\UserRole
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param array $options options
     *
     * @return Result
     */
    public function fetchAll($options = []);

    /**
     * add
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    role Id
     *
     *    -- fail
     *    null
     *
     * @param User   $user      user
     * @param string $aclRoleId aclRoleId
     *
     * @return Result
     */
    public function add(
        User $user,
        $aclRoleId
    );

    /**
     * remove
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    role Id
     *
     *    -- fail
     *    null
     *
     * @param User   $user      user
     * @param string $aclRoleId aclRoleId
     *
     * @return Result
     */
    public function remove(
        User $user,
        $aclRoleId
    );

    /**
     * create
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param User  $user  user
     * @param array $roles roles
     *
     * @return Result
     */
    public function create(
        User $user,
        $roles = []
    );

    /**
     * read
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param User $user user
     *
     * @return Result
     */
    public function read(User $user);

    /**
     * update
     *
     * RETURN DATA FORMAT:
     *
     *    -- success (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     * @param User  $user  user
     * @param array $roles roles
     *
     * @return Result
     */
    public function update(
        User $user,
        $roles = []
    );

    /**
     * delete
     *
     * RETURN DATA FORMAT:
     *
     *    -- success (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     * @param User  $user  user
     * @param array $roles roles
     *
     * @return Result
     */
    public function delete(
        User $user,
        $roles = []
    );
}
