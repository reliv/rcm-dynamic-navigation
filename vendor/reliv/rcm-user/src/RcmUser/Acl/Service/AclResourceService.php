<?php
/**
 * AclResourceService.php
 *
 * AclResourceService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Acl\Service;

use
    RcmUser\Acl\Entity\AclResource;
use
    RcmUser\Acl\Provider\ResourceProvider;
use
    RcmUser\Acl\Provider\ResourceProviderInterface;
use
    RcmUser\Exception\RcmUserException;
use
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AclResourceService
 *
 * AclResourceService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclResourceService
{
    /**
     * @var ServiceLocatorInterface $serviceLocator
     */
    protected $serviceLocator;

    /**
     * @var AclResource $rootResource
     */
    protected $rootResource = null;

    /**
     * @var array $resourceProviders
     */
    protected $resourceProviders = [];

    /**
     * @var array $resources
     */
    protected $resources = [];

    /**
     * @var bool $isCached
     */
    protected $isCached = false;

    /**
     * @var int $maxResourceNesting
     */
    protected $maxResourceNesting = 10;

    /**
     * __construct
     *
     * @param AclResource|array $rootResource rootResource
     */
    public function __construct($rootResource)
    {
        $this->rootResource = $this->buildValidResource(
            $rootResource,
            null
        );
    }

    /**
     * setServiceLocator
     *
     * @param ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * getServiceLocator
     *
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * getRootResource
     *
     * @return AclResource
     */
    public function getRootResource()
    {
        return $this->rootResource;
    }

    /**
     * setResourceProviders
     *
     * @param array $resourceProviders resourceProviders
     *
     * @return void
     */
    public function setResourceProviders($resourceProviders)
    {
        $this->resourceProviders = $resourceProviders;
    }

    /**
     * getResourceProviders
     *
     * @return array
     */
    public function getResourceProviders()
    {
        return $this->resourceProviders;
    }

    /**
     * setMaxResourceNesting
     *
     * @param int $maxResourceNesting Max nesting levels for resources
     *
     * @return void
     */
    public function setMaxResourceNesting($maxResourceNesting)
    {
        $this->maxResourceNesting = (int)$maxResourceNesting;
    }

    /**
     * getMaxResourceNesting
     *
     * @return int
     */
    public function getMaxResourceNesting()
    {
        return $this->maxResourceNesting;
    }

    /**
     * getResources - Get a resource and all of its parents
     *
     * @param string $resourceId resourceId
     * @param string $providerId providerId
     *
     * @return array
     */
    public function getResources(
        $resourceId,
        $providerId = null
    ) {
        $resourceId = strtolower($resourceId);

        // add root
        $this->addRootResource($this->resources);

        if (empty($providerId)) {

            $provider = $this->getProviderByResourceId($resourceId);

        } else {

            $provider = $this->getProvider($providerId);
        }

        if (empty($provider)) {

            // return root resource at least
            return $this->resources;
        }

        // get resource
        $resource = $provider->getResource($resourceId);

        // check for empty resource
        if (empty($resource)) {

            // resource not found
            return [];
        }

        $resource = $this->buildValidAclResource(
            $resource,
            $providerId
        );

        $resources = $this->getResourceStack(
            $provider,
            $resource
        );

        $this->resources = $this->resources + $resources;

        return $this->resources;
    }

    /**
     * getAllResources - All resources
     * returns a list of all resources
     * This is used for displays or utilities only
     * should not be used for ACL checks
     *
     * @param bool $refresh refresh
     *
     * @return array
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function getAllResources($refresh = false)
    {
        if (!$this->isCached || $refresh) {

            // add root
            $this->addRootResource($this->resources);

            foreach ($this->resourceProviders as $providerId => &$provider) {

                $provider = $this->buildValidProvider(
                    $provider,
                    $providerId
                );

                $providerResources = $provider->getResources();

                foreach ($providerResources as &$resource) {

                    $resource = $this->buildValidAclResource(
                        $resource,
                        $providerId
                    );
                    $resourceId = $resource->getResourceId();

                    if (!isset($this->resources[$resourceId])) {

                        $newResources = $this->getResources(
                            $resourceId,
                            $providerId
                        );
                        $this->resources = $this->resources + $newResources;
                    }
                }
            }

            $this->isCached = true;
        }

        return $this->resources;
    }

    /**
     * getResourceStack - build a resource stack for the
     * provided resource to the top parent
     *
     * @param ResourceProviderInterface $provider   provider
     * @param AclResource               $resource   resource
     * @param array                     &$resources resources
     * @param int                       $nestLevel  nestLevel
     *
     * @return array
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function getResourceStack(
        ResourceProviderInterface $provider,
        AclResource $resource,
        &$resources = [],
        $nestLevel = 0
    ) {
        if ($nestLevel > $this->maxResourceNesting) {

            throw new RcmUserException(
                'Max resource nesting exceded, max nesting level is '
                . $this->maxResourceNesting
            );
        }

        //$resources[$resource->getResourceId()] = $resource;
        $tempResource = [$resource->getResourceId() => $resource];
        $resources = $tempResource + $resources;

        $parentId = $resource->getParentResourceId();

        $hasParent = false;
        $parentResource = null;

        if ($resource->getParentResource() instanceof AclResource) {

            $hasParent = true;
            $parentResource = $resource->getParentResource();
        } elseif (!empty($parentId)) {

            $hasParent = true;
            $parentResource = $provider->getResource($parentId);
        }

        if ($hasParent && empty($parentResource)) {

            $resources[$resource->getResourceId()]->setParentResourceId(
                $this->rootResource->getResourceId()
            );
        }

        if ($hasParent && !empty($parentResource)) {

            $parentResource = $this->buildValidAclResource(
                $parentResource,
                $provider->getProviderId()
            );

            return $this->getResourceStack(
                $provider,
                $parentResource,
                $resources
            );
        }

        // set parent root
        return $resources;
    }

    /**
     * getProvider
     *
     * @param string $providerId providerId
     *
     * @return null
     */
    public function getProvider($providerId)
    {
        if (!isset($this->resourceProviders[$providerId])) {
            return null;
        }

        // @codingStandardsIgnoreStart
        if (!($this->resourceProviders[$providerId] instanceof
            ResourceProviderInterface)
        ) {
            $this->resourceProviders[$providerId] = $this->buildValidProvider(
                $this->resourceProviders[$providerId],
                $providerId
            );
        }
        // @codingStandardsIgnoreEnd
        return $this->resourceProviders[$providerId];
    }

    /**
     * getProviderByResourceId
     *
     * @param string $resourceId resourceId
     *
     * @return ResourceProviderInterface
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function getProviderByResourceId($resourceId)
    {
        $resourceId = strtolower($resourceId);

        foreach ($this->resourceProviders as $providerId => &$provider) {

            $provider = $this->buildValidProvider(
                $provider,
                $providerId
            );

            $resource = $provider->getResource($resourceId);

            if (!empty($resource)) {
                return $provider;
            }
        }

        throw new RcmUserException(
            "Provide could not be found for resource: {$resourceId}"
        );
    }

    /**
     * getProviderByResourceId
     *
     * @param string $resourceId resourceId
     *
     * @return null|ResourceProviderInterface

    public function getProviderByResourceId($resourceId)
     * {
     * foreach ($this->resourceProviders as $providerId => &$provider) {
     *
     * $provider = $this->buildValidProvider($provider, $providerId);
     *
     * if ($provider->hasResource($resourceId)) {
     *
     * return $provider;
     * }
     * }
     *
     * return null;
     * }
     */

    /**
     * getResourcesWithNamespace
     *
     * @param string $resourceId resourceId
     * @param string $providerId providerId
     * @param bool   $refresh    refresh
     * @param string $nsChar     nsChar
     *
     * @return array
     */
    public function getResourcesWithNamespace(
        $resourceId = null,
        $providerId = null,
        $refresh = false,
        $nsChar = '.'
    ) {
        $resourceId = strtolower($resourceId);
        $aclResources = [];
        $resources = $this->getNamespacedResources(
            $resourceId,
            $providerId,
            $refresh,
            $nsChar
        );

        foreach ($resources as $ns => $res) {

            $resourceId = $res->getResourceId();
            $aclResources[$resourceId] = [];
            $aclResources[$resourceId]['resource'] = $res;
            $aclResources[$resourceId]['resourceNs'] = $ns;
        }

        return $aclResources;
    }

    /**
     * getNamespacedResources
     *
     * @param string $resourceId resourceId
     * @param string $providerId providerId
     * @param bool   $refresh    refresh
     * @param string $nsChar     nsChar
     *
     * @return array
     */
    public function getNamespacedResources(
        $resourceId = null,
        $providerId = null,
        $refresh = false,
        $nsChar = '.'
    ) {
        $aclResources = [];

        $resourceId = strtolower($resourceId);

        if (empty($resourceId)) {

            // get general list, not dynamic resources
            $resources = $this->getAllResources($refresh);
        } else {

            $resources = $this->getResources(
                $resourceId,
                $providerId
            );
        }

        foreach ($resources as $res) {

            $ns = $this->createNamespaceId(
                $res,
                $resources,
                $nsChar
            );

            $aclResources[$ns] = $res;
        }

        ksort($aclResources);

        return $aclResources;
    }

    /**
     * createNamespaceId
     *
     * @param AclResource $aclResource  aclResource
     * @param array       $aclResources aclResources
     * @param string      $nsChar       nsChar
     *
     * @return string
     */
    public function createNamespaceId(
        AclResource $aclResource,
        $aclResources,
        $nsChar = '.'
    ) {
        $parentId = $aclResource->getParentResourceId();
        $ns = $aclResource->getResourceId();
        if (!empty($parentId) && isset($aclResources[$parentId])) {

            $parent = $aclResources[$parentId];

            $newns = $this->createNamespaceId(
                $parent,
                $aclResources,
                $nsChar
            );

            $ns = $newns . $nsChar . $ns;
        }

        return $ns;
    }

    /**
     * addRootResource
     *
     * @param array &$resources resources
     *
     * @return array
     */
    protected function addRootResource(&$resources)
    {

        // $first_value = reset($array);
        $rootResource = $this->getRootResource();

        if (!isset($resources[$rootResource->getResourceId()])) {

            $tempRootResource = [
                $rootResource->getResourceId() => $rootResource
            ];

            $resources = $tempRootResource + $tempRootResource;
        }

        return $resources;
    }

    /**
     * buildValidAclResource
     *
     * @param mixed  $resource   resource
     * @param string $providerId providerId
     *
     * @return AclResource
     */
    public function buildValidAclResource(
        $resource,
        $providerId
    ) {
        $resource = $this->buildValidResource(
            $resource,
            $providerId
        );
        $resource = $this->buildValidParent($resource);

        return $resource;
    }

    /**
     * buildValidResource
     *
     * @param mixed  $resourceData resource
     * @param string $providerId   providerId
     *
     * @return AclResource
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function buildValidResource(
        $resourceData,
        $providerId
    ) {
        if ($resourceData instanceof AclResource) {

            $resource = $resourceData;
        } elseif (is_array($resourceData)) {

            $resource = new AclResource($resourceData['resourceId']);
            $resource->populate($resourceData);
        } else {

            throw new RcmUserException(
                'Resource is not valid: ' . var_export(
                    $resourceData,
                    true
                )
            );
        }

        $resourceProviderId = $resource->getProviderId();
        if (empty($resourceProviderId)) {

            $resource->setProviderId($providerId);
        }

        return $resource;
    }

    /**
     * buildValidParent
     *
     * @param AclResource $resource resource
     *
     * @return AclResource
     */
    public function buildValidParent(AclResource $resource)
    {
        $parentId = $resource->getParentResourceId();

        if (empty($parentId)) {

            $resource->setParentResourceId(
                $this->rootResource->getResourceId()
            );
        }

        return $resource;
    }

    /**
     * buildValidProvider
     *
     * @param mixed  $providerData providerData
     * @param string $providerId   providerId
     *
     * @return ResourceProviderInterface
     * @throws \RcmUser\Exception\RcmUserException
     */
    protected function buildValidProvider(
        $providerData,
        $providerId
    ) {
        $provider = null;

        if ($providerData instanceof ResourceProviderInterface) {

            $provider = $providerData;
        } elseif (is_string($providerData)) {

            $provider = $this->getServiceLocator()->get($providerData);
        } elseif (is_array($providerData)) {

            $provider = new ResourceProvider($providerData);
        }

        if (!($provider instanceof ResourceProviderInterface)) {

            throw new RcmUserException(
                'ResourceProvider is not valid: ' . var_export(
                    $providerData,
                    true
                )
            );
        }

        $provider->setProviderId($providerId);

        return $provider;
    }
}
