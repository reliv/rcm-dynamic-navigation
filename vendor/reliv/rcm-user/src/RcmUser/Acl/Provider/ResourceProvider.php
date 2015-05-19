<?php
/**
 * ResourceProvider.php
 *
 * ResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Acl\Provider;

/**
 * class ResourceProvider
 *
 * ResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ResourceProvider implements ResourceProviderInterface
{
    /**
     * @var string $providerId
     */
    protected $providerId = null;
    /**
     * @var array $resources
     */
    protected $resources = [];

    /**
     * __construct
     *
     * @param array $resources resources
     */
    public function __construct($resources)
    {
        if (is_array($resources)) {
            $this->resources = $resources;
        }
    }

    /**
     * setProviderId
     *
     * @param string $providerId providerId
     *
     * @return void
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;
    }

    /**
     * getProviderId
     *
     * @return string
     */
    public function getProviderId()
    {
        return $this->providerId;
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
        return $this->resources;
    }

    /**
     * getResource
     * Return the requested resource or null if not found
     * Can be used to return resources dynamically at run-time
     *
     * @param string $resourceId resourceId
     *
     * @return AclResource|array|null
     */
    public function getResource($resourceId)
    {
        if (isset($this->resources[$resourceId])) {
            return $this->resources[$resourceId];
        }

        return null;
    }
}
