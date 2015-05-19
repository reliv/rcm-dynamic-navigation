<?php
/**
 * DoctrineAclRole.php
 *
 * DoctrineAclRole
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Acl\Entity;

use
    Doctrine\ORM\Mapping as ORM;

/**
 * DoctrineAclRole
 *
 * DoctrineAclRole
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_user_acl_role")
 */
class DoctrineAclRole extends AclRole
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $parentRoleId;
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
     */
    protected $roleId;
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @todo This probably can't be made to work
     * -- Needs to accept null value if this is in the root of the tree
     * -- needs to only nest the parent, not the whole parent tree
     * -- There is an issue with zend acl, since this in traversable, it will not work
     * ORM\ManyToOne(targetEntity="DoctrineAclRole", inversedBy="children")
     * ORM\JoinColumn(name="parentRoleId", referencedColumnName="roleId")
     *       , onDelete="SET NULL"
     **/
    protected $parentRole;

    /**
     * ORM\OneToMany(targetEntity="DoctrineAclRole", mappedBy="parentRole")
     * ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * setId
     *
     * @param int $id id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * getId
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
