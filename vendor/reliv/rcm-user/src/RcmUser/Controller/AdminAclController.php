<?php
/**
 * AdminAclController.php
 *
 * AdminAclController
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

use
    RcmUser\Provider\RcmUserAclResourceProvider;

/**
 * Class AdminAclController
 *
 * AdminAclController
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
class AdminAclController extends AbstractAdminController
{
    /**
     * indexAction
     *
     * @return array
     */
    public function indexAction()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $viewArr = [];

        return $this->buildView($viewArr);
    }
}
