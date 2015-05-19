<?php
/**
 * UserRoleInterface.php
 *
 * UserRoleInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Entity;

/**
 * Interface UserRoleInterface
 *
 * UserRoleInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface UserRoleInterface
{
    /**
     * setId
     *
     * @param mixed $id id
     *
     * @return void
     */
    public function setId($id);

    /**
     * getId
     *
     * @return mixed
     */
    public function getId();

    /**
     * setRoleId
     *
     * @param mixed $roleId roleId
     *
     * @return void
     */
    public function setRoleId($roleId);

    /**
     * getRoleId
     *
     * @return mixed
     */
    public function getRoleId();

    /**
     * setUserId
     *
     * @param mixed $userId userId
     *
     * @return void
     */
    public function setUserId($userId);

    /**
     * getUserId
     *
     * @return mixed
     */
    public function getUserId();
}
