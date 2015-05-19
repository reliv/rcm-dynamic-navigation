<?php
/**
 * AdminApiAclRoleController.php
 *
 * AdminApiAclRoleController
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
    RcmUser\Acl\Entity\AclRole;
use
    RcmUser\Acl\Entity\AclRule;
use
    RcmUser\Provider\RcmUserAclResourceProvider;
use
    Zend\View\Model\JsonModel;

/**
 * Class AdminApiAclRoleController
 *
 * AdminApiAclRoleController
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
class AdminApiAclRoleController extends AbstractAdminApiController
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

        /** @var \RcmUser\Acl\Service\AclDataService $aclDataService */
        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        try {

            $result = $aclDataService->getNamespacedRoles();
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * get
     *
     * @param string $id id
     *
     * @return mixed|JsonModel
     */
    public function get($id)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        try {

            $aclRole = new AclRole();
            $aclRole->setRoleId((string)$id);
            $result = $aclDataService->readRole($aclRole);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * create
     *
     * @param mixed|AclRule $data data
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'create'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        try {

            $aclRole = new AclRole();
            $aclRole->populate($data);
            $result = $aclDataService->createRole($aclRole);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * delete
     *
     * @param string $id id
     *
     * @return mixed|JsonModel
     */
    public function delete($id)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'delete'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        try {

            $aclRole = new AclRole();
            $aclRole->setRoleId((string)$id);

            $result = $aclDataService->deleteRole($aclRole);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }
}
