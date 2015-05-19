<?php
/**
 * DoctrineAclRule.php
 *
 * DoctrineAclRule
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
 * DoctrineAclRule
 *
 * DoctrineAclRule
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
 * @ORM\Table(name="rcm_user_acl_rule")
 */
class DoctrineAclRule extends AclRule
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $roleId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $rule = AclRule::RULE_ALLOW;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $resourceId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $privilege;

    /**
     * setId
     *
     * @param int $id id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
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
