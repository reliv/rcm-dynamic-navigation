<?php
namespace RcmUser\View\Helper;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * Class RcmUserHasRoleBasedAccess
 *
 * RcmUserHasRoleBasedAccess
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserHasRoleBasedAccess extends AbstractHelper
{

    /**
     * @var AuthorizeService
     */
    protected $authorizeService;

    /**
     * @var UserAuthenticationService $userAuthService
     */
    protected $userAuthService;

    /**
     * __construct
     *
     * @param AuthorizeService          $authorizeService authorizeService
     * @param UserAuthenticationService $userAuthService  userAuthService
     */
    public function __construct(
        AuthorizeService $authorizeService,
        UserAuthenticationService $userAuthService
    ) {
        $this->authorizeService = $authorizeService;
        $this->userAuthService = $userAuthService;
    }

    /**
     * __invoke
     *
     * @param $roleId
     *
     * @return bool
     */
    public function __invoke(
        $roleId
    ) {
        $user = $this->userAuthService->getIdentity();

        if (!($user instanceof User)) {
            return false;
        }

        return $this->authorizeService->hasRoleBasedAccess($user, $roleId);
    }
}
