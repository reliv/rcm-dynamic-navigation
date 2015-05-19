<?php
/**
 * AclResource.php
 *
 * AclResource
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Acl\Entity;

use
    RcmUser\Exception\RcmUserException;
use
    Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class AclResource
 *
 * AclResource
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclResource extends GenericResource implements \JsonSerializable
{
    /**
     * @var string $providerId The resource provider Id
     */
    protected $providerId = null;

    /**
     * @var string $parentResourceId
     */
    protected $parentResourceId = null;

    /**
     * @var AclResource $parentResource
     */
    protected $parentResource = null;

    /**
     * @var array $privileges
     */
    protected $privileges = [];

    /**
     * @var string $name
     */
    protected $name = '';

    /**
     * @var string $description
     */
    protected $description = '';

    /**
     * __construct
     *
     * @param string $resourceId       resourceId
     * @param null   $parentResourceId parentResourceId
     * @param array  $privileges       privileges
     */
    public function __construct(
        $resourceId,
        $parentResourceId = null,
        $privileges = []
    ) {
        $this->setResourceId($resourceId);
        $this->setParentResourceId($parentResourceId);
        $this->setPrivileges($privileges);
    }

    /**
     * setResourceId
     *
     * @param string $resourceId resourceId
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setResourceId($resourceId)
    {
        // set to lowercase to avoid overlaps
        $resourceId = strtolower((string)$resourceId);

        if (!$this->isValidResourceId($resourceId) || empty($resourceId)) {

            throw new RcmUserException("Resource resourceId ({$resourceId}) is invalid.");
        }

        $this->resourceId = $resourceId;
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
     * setParentResourceId
     *
     * @param string $parentResourceId parentResourceId
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setParentResourceId($parentResourceId)
    {
        // set to lowercase to avoid overlaps
        $parentResourceId = strtolower((string)$parentResourceId);

        if (!$this->isValidResourceId($parentResourceId)) {

            throw new RcmUserException("Resource parentResourceId ({$parentResourceId}) is invalid.");
        }

        if (!empty($this->parentResource)) {

            if ($this->parentResource->getResourceId() !== $parentResourceId) {

                $this->parentResource = null;
            }
        }

        if (empty($parentResourceId)) {

            $parentResourceId = null;
        }

        $this->parentResourceId = $parentResourceId;
    }

    /**
     * getParentResourceId
     *
     * @return string
     */
    public function getParentResourceId()
    {
        return $this->parentResourceId;
    }

    /**
     * setParentResource
     *
     * @param AclResource $parentResource parentResource
     *
     * @return void
     */
    public function setParentResource(AclResource $parentResource)
    {
        $this->setParentResourceId($parentResource->getResourceId());
        $this->parentResource = $parentResource;
    }

    /**
     * getParentResource
     *
     * @return string|AclResource
     */
    public function getParentResource()
    {
        if (empty($this->parentResource)) {
            return $this->getParentResourceId();
        }

        return $this->parentResource;
    }

    /**
     * setPrivileges
     *
     * @param array $privileges privileges
     *
     * @return void
     */
    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }

    /**
     * getPrivileges
     *
     * @return array
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * setName
     *
     * @param string $name name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        if (empty($this->name)) {
            return $this->getResourceId();
        }

        return $this->name;
    }

    /**
     * setDescription
     *
     * @param string $description description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * isValidResourceId
     *
     * @param string $resourceId resourceId
     *
     * @return bool
     */
    public function isValidResourceId($resourceId)
    {
        if (preg_match(
            '/[^a-z_\-0-9\.]/i',
            $resourceId
        )
        ) {
            return false;
        }

        return true;
    }

    /**
     * populate
     *
     * @param array $data data
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function populate($data = [])
    {
        if (($data instanceof AclResource)) {

            $this->setResourceId($data->getResourceId());
            $this->setProviderId($data->getProviderId());
            $this->setParentResourceId($data->getParentResourceId());
            $this->setPrivileges($data->getPrivileges());
            $this->setName($data->getName());
            $this->setDescription($data->getDescription());

            return;
        }

        if (is_array($data)) {
            if (isset($data['resourceId'])) {
                $this->setResourceId($data['resourceId']);
            }
            if (isset($data['providerId'])) {
                $this->setProviderId($data['providerId']);
            }
            if (isset($data['parentResourceId'])) {
                $this->setParentResourceId($data['parentResourceId']);
            }
            if (isset($data['privileges'])) {
                $this->setPrivileges($data['privileges']);
            }
            if (isset($data['name'])) {
                $this->setName($data['name']);
            }
            if (isset($data['description'])) {
                $this->setDescription($data['description']);
            }

            return;
        }

        throw new RcmUserException('Resource data could not be populated, data format not supported');
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();
        $obj->resourceId = $this->getResourceId();
        $obj->providerId = $this->getProviderId();
        $obj->parentResourceId = $this->getParentResourceId();
        $obj->privileges = $this->getPrivileges();
        $obj->name = $this->getName();
        $obj->description = $this->getDescription();

        return $obj;
    }
}
