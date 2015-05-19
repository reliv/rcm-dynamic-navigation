<?php
/**
 * RcmUserAclResourceProvider.php
 *
 * RcmUserAclResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Provider;

use
    RcmUser\Acl\Entity\AclResource;
use
    RcmUser\Acl\Provider\ResourceProvider;

/**
 * RcmUserAclResourceProvider
 *
 * RcmUserAclResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserAclResourceProvider extends ResourceProvider
{
    /**
     * @var string PROVIDER_ID This needs to be the same as the config
     */
    const PROVIDER_ID = 'RcmUser';

    /**
     * @var string RESOURCE_ID_ROOT
     */
    const RESOURCE_ID_ROOT = 'rcmuser';

    /**
     * @var string RESOURCE_ID_ACL
     */
    const RESOURCE_ID_ACL = 'rcmuser-acl-administration';

    /**
     * @var string RESOURCE_ID_USER
     */
    const RESOURCE_ID_USER = 'rcmuser-user-administration';

    /**
     * default resources  - rcm user needs these,
     * however descriptions added on construct in the factory
     *
     * @var array $rcmResources
     */
    protected $resources = [];

    /**
     * __construct
     *
     */
    public function __construct()
    {
    }

    /**
     * getResources (ALL resources)
     * Return a multi-dimensional array of resources and privileges
     * containing ALL possible resources including run-time resources
     *
     * @return array
     */
    public function getResources()
    {
        if (empty($this->resources)) {

            $this->buildResources();
        }

        return $this->resources;
    }

    /**
     * getResource
     * Return the requested resource
     * Can be used to return resources dynamically.
     *
     * @param string $resourceId resourceId
     *
     * @return array
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function getResource($resourceId)
    {

        if (empty($this->resources)) {

            $this->buildResources();
        }

        return parent::getResource($resourceId);
    }

    /**
     * buildResources - build static resources
     *
     * @return void
     */
    protected function buildResources()
    {
        $privileges = [
            'read',
            'update',
            'create',
            'delete',
        ];

        $userPrivileges = [
            'read',
            'update',
            'create',
            'delete',
            'update_credentials',
        ];

        /* parent resource */
        $this->resources[self::RESOURCE_ID_ROOT]
            = new AclResource(self::RESOURCE_ID_ROOT);
        $this->resources[self::RESOURCE_ID_ROOT]->setName(
            'RCM User'
        );
        $this->resources[self::RESOURCE_ID_ROOT]->setDescription(
            'All RCM user access.'
        );
        $this->resources[self::RESOURCE_ID_ROOT]->setPrivileges(
            $privileges
        );

        /* user edit */
        $this->resources[self::RESOURCE_ID_USER]
            = new AclResource(self::RESOURCE_ID_USER, self::RESOURCE_ID_ROOT, $userPrivileges);
        $this->resources[self::RESOURCE_ID_USER]->setName(
            'User Administration'
        );
        $this->resources[self::RESOURCE_ID_USER]->setDescription(
            'Allows the editing of user data.'
        );

        /* access and roles */
        $this->resources[self::RESOURCE_ID_ACL]
            = new AclResource(self::RESOURCE_ID_ACL, self::RESOURCE_ID_ROOT, $privileges);
        $this->resources[self::RESOURCE_ID_ACL]->setName(
            'Role and Access Administration'
        );
        $this->resources[self::RESOURCE_ID_ACL]->setDescription(
            'Allows the editing of user access and role data.'
        );
    }
}
