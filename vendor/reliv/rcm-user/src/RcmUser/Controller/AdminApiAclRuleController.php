<?php
/**
 * AdminApiAclRuleController.php
 *
 * AdminApiAclRuleController
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
    RcmUser\Acl\Entity\AclRule;
use
    RcmUser\Provider\RcmUserAclResourceProvider;
use
    Zend\View\Model\JsonModel;

/**
 * Class AdminApiAclRuleController
 *
 * AdminApiAclRuleController
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
class AdminApiAclRuleController extends AbstractAdminApiController
{
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

        return new JsonModel(['get' . $id]);
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

        /** @var \RcmUser\Acl\Service\AclDataService $aclDataService */
        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        try {

            $aclRule = new AclRule();
            $aclRule->populate($data);
            $result = $aclDataService->createRule($aclRule);
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
            $data = json_decode(
                $this->getRequest()->getContent(),
                true
            );
            //$data = json_decode(urldecode($id), true);

            $aclRule = new AclRule();
            $aclRule->populate($data);
            $result = $aclDataService->deleteRule($aclRule);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }
}
