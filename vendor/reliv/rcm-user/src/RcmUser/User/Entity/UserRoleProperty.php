<?php
/**
 * UserRoleProperty.php
 *
 * UserRoleProperty
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

use
    RcmUser\Exception\RcmUserException;

/**
 * Class UserRoleProperty
 *
 * UserRoleProperty
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
class UserRoleProperty implements UserPropertyInterface
{
    /**
     * @var string
     */
    const PROPERTY_KEY = 'RcmUserUserRoles';
    /**
     * @var array $roles
     */
    protected $roles = [];

    /**
     * __construct
     *
     * @param array $roles role id list
     */
    public function __construct(
        $roles = []
    ) {
        $this->setRoles($roles);
    }

    /**
     * setRoles
     *
     * @param array $roles array of Role Ids
     *
     * @return void
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * getRoles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * setRole
     *
     * @param string $roleId roleId
     *
     * @return void
     */
    public function setRole($roleId)
    {
        if (!$this->hasRole($roleId)) {

            $this->roles[] = $roleId;
        }
    }

    /**
     * getRole
     *
     * @param string $roleId  roleId
     * @param mixed  $default default
     *
     * @return null
     */
    public function getRole(
        $roleId,
        $default = null
    ) {
        $key = array_search(
            $roleId,
            $this->roles
        );

        if ($key === false) {
            return $default;
        }

        return $this->roles[$key];
    }

    /**
     * hasRoles
     *
     * @return bool
     */
    public function hasRoles()
    {
        return !empty($this->roles);
    }

    /**
     * hasRole
     *
     * @param string $roleId role
     *
     * @return bool
     */
    public function hasRole($roleId)
    {
        if ($this->getRole(
            $roleId,
            false
        )
        ) {
            return true;
        }

        return false;
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this->getRoles();
    }

    /**
     * populate
     *
     * @param array $data data
     *
     * @return void
     * @throws RcmUserException
     */
    public function populate($data = [])
    {
        if (($data instanceof UserRoleProperty)) {

            $this->setRoles($data->getRoles());

            return;
        }

        if (is_array($data)) {

            $this->setRoles($data);

            return;
        }

        throw new RcmUserException(
            'Object ' . get_class($this) . ' '
            . 'could not be populated, date format not supported'
        );
    }
}
