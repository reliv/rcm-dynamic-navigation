<?php
/**
 * AbstractAdminController.php
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

use
    RcmUser\Provider\RcmUserAclResourceProvider;
use
    Zend\Http\Response;
use
    Zend\Mvc\Controller\AbstractActionController;
use
    Zend\View\Model\ViewModel;

/**
 * Class AbstractAdminController
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
class AbstractAdminController extends AbstractActionController
{

    /**
     * isAllowed
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     *
     * @return mixed
     */
    public function isAllowed(
        $resourceId = RcmUserAclResourceProvider::RESOURCE_ID_ROOT,
        $privilege = null
    ) {
        return $this->rcmUserIsAllowed(
            $resourceId,
            $privilege,
            RcmUserAclResourceProvider::PROVIDER_ID
        );
    }

    /**
     * getNotAllowedResponse
     *
     * @return mixed
     */
    public function getNotAllowedResponse()
    {
        $response = $this->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_401);
        $response->setContent($response->renderStatusLine());

        return $response;
    }

    /**
     * buildView
     *
     * @param array $viewArr viewArr
     *
     * @return ViewModel
     */
    protected function buildView($viewArr = [])
    {
        $view = new ViewModel($viewArr);

        return $view;
    }
}
