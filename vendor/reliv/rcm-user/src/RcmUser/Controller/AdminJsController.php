<?php
/**
 * AdminJsController.php
 *
 * LongDescHere
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

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\User\Entity\UserRoleProperty;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class AdminJsController
 *
 * LongDescHere
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
class AdminJsController extends AbstractAdminController
{

    /**
     * getJsView
     *
     * @param string $template
     * @param array  $variables
     *
     * @return ViewModel
     */
    protected function getJsView($template, $variables = array())
    {
        $viewModel = new ViewModel($variables);

        $viewModel->setTemplate($template);
        $viewModel->setTerminal(true);

        $response = $this->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_200);
        $response->getHeaders()->addHeaders(
            array(
                'Content-Type' => 'application/javascript'
            )
        );

        return $viewModel;
    }

    /**
     * adminCoreAction
     *
     * @return ViewModel
     */
    public function adminCoreAction()
    {
        // @todo ACCESS CHECK?
        // This is a shared service, so what level access should we require?

        return $this->getJsView('js/rcmuser.core.js');
    }

    /**
     * adminAclAction
     *
     * @return ViewModel
     */
    public function adminAclAction()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read'
        )) {
            return $this->getNotAllowedResponse();
        }

        /** @var $aclDataService \RcmUser\Acl\Service\AclDataService */
        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        $superAdminRoleId = $aclDataService->getSuperAdminRoleId()->getData();
        $guestRoleId = $aclDataService->getGuestRoleId()->getData();


        return $this->getJsView(
            'js/rcmuser.admin.acl.app.js',
            array(
                'superAdminRoleId' => $superAdminRoleId,
                'guestRoleId' => $guestRoleId,
            )
        );
    }

    /**
     * adminUsersAction
     *
     * @return mixed|ViewModel
     */
    public function adminUsersAction()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'read'
        )) {
            return $this->getNotAllowedResponse();
        }

        $rolePropertyId = UserRoleProperty::PROPERTY_KEY;

        return $this->getJsView(
            'js/rcmuser.admin.users.app.js',
            array(
                'rolePropertyId' => $rolePropertyId,
            )
        );
    }

    /**
     * adminUserRolesAction
     *
     * @return mixed|ViewModel
     */
    public function adminUserRolesAction()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'read'
        )) {
            return $this->getNotAllowedResponse();
        }

        return $this->getJsView(
            'js/rcmuser.admin.user.role.app.js'
        );
    }
}
