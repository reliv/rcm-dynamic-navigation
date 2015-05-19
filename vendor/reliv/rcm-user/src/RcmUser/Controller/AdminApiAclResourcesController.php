<?php
/**
 * AdminApiAclResourcesController.php
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
    RcmUser\Result;
use
    Zend\View\Model\JsonModel;

/**
 * Class AdminApiAclResourcesController
 *
 * AdminApiAclResourcesController
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
class AdminApiAclResourcesController extends AbstractAdminApiController
{
    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $aclResourceService = $this->getServiceLocator()->get(
            'RcmUser\Acl\Service\AclResourceService'
        );

        try {

            $resources = $aclResourceService->getResourcesWithNamespace();
            $result = new Result($resources);

        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }
}
