<?php
/**
 * AdminUserController.php
 *
 * AdminUserController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Controller;

/**
 * Class AdminUserRoleController
 *
 * AdminUserRoleController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AdminUserRoleController extends AbstractAdminController
{
    /**
     * indexAction - list
     *
     * @return array
     */
    public function indexAction()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $viewArr = [];

        return $this->buildView($viewArr);
    }
}
