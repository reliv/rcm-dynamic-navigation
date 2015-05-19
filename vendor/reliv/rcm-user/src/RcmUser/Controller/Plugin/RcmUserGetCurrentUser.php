<?php
/**
 * RcmUserGetCurrentUser.php
 *
 * RcmUserGetCurrentUser
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmUser\Controller\Plugin;

use
    RcmUser\Service\RcmUserService;
use
    Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * RcmUserGetCurrentUser
 *
 * RcmUserGetCurrentUser
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserGetCurrentUser extends AbstractPlugin
{

    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * __construct
     *
     * @param RcmUserService $rcmUserService rcmUserService
     */
    public function __construct(
        RcmUserService $rcmUserService
    ) {
        $this->rcmUserService = $rcmUserService;
    }

    /**
     * __invoke
     *
     * @param mixed $default default
     *
     * @return null|\RcmUser\User\Entity\User
     */
    public function __invoke($default = null)
    {
        $user = $this->rcmUserService->getIdentity($default);

        return $user;
    }
}
