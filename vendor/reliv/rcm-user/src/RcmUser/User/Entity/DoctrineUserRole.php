<?php
/**
 * DoctrineUserRole.php
 *
 * DoctrineUserRole
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
    Doctrine\ORM\Mapping as ORM;

/**
 * Class DoctrineUserRole
 *
 * DoctrineUserRole
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
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_user_user_role")
 */
class DoctrineUserRole extends UserRole
{
    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string $userId
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $userId;
    /**
     * @var string $roleId
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $roleId;
}
