<?php


namespace RcmUser\Acl\Entity;


/**
 * Class NamespaceAclRole
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class NamespaceAclRole extends AclRole
{
    /**
     * A namespace defining location in the tree of roles guest.user.admin
     * @var string
     */
    protected $namespace = '';

    /**
     * getNamespace
     *
     * @return string
     */
    public function getNamespace()
    {
        if (empty($this->namespace)) {
            return $this->getRoleId();
        }
        return $this->namespace;
    }

    /**
     * setNamespace -
     *
     * @param $namespace
     *
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $obj = parent::jsonSerialize();
        $obj->namespace = $this->getNamespace();

        return $obj;
    }
}