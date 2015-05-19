<?php
/**
 * Class DoctrineUser
 *
 * DoctrineUser
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
 * Class DoctrineUser
 *
 * DoctrineUser
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
 * @ORM\Table(name="rcm_user_user")
 */
class DoctrineUser extends User
{
    /**
     * @var string $id
     * @ORM\Id
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $id = null;

    /**
     * @var string $username
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $username;

    /**
     * @var string $password
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @var string $state
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $state = User::STATE_DISABLED;

    /**
     * @var string $email
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $email;

    /**
     * @var string $name
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $name;
}
