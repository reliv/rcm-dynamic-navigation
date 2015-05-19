<?php
/**
 * AdminApiUserValidUserStatesController.php
 *
 * AdminApiUserValidUserStatesController
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
    RcmUser\Result;
use
    Zend\View\Model\JsonModel;

/**
 * Class AdminApiUserValidUserStatesController
 *
 * AdminApiUserValidUserStatesController
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
class AdminApiUserValidUserStatesController extends AbstractAdminApiController
{

    /**
     * getList
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface|JsonModel
     */
    public function getList()
    {
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserDataService $userDataService */
        $userDataService = $this->getServiceLocator()->get(
            'RcmUser\User\Service\UserDataService'
        );

        try {

            $result = new Result($userDataService->getValidUserStates());
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }
}
