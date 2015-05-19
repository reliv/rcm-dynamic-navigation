<?php
/**
 * UserInterface.php
 *
 * UserInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Entity;

/**
 * Interface UserInterface
 *
 * UserInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface UserInterface extends \IteratorAggregate
{

    /**
     * set a property
     *
     * @param string $property
     * @param mixed $value
     *
     * @return bool
     */
    public function set($property, $value);

    /**
     * get a property
     *
     * @param string $property
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($property, $default = null);

    /**
     * setId
     *
     * @param mixed $id id
     *
     * @return void
     */
    public function setId($id);

    /**
     * getId
     *
     * @return mixed
     */
    public function getId();

    /**
     * setPassword
     *
     * @param string $password password
     *
     * @return void
     */
    public function setPassword($password);

    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword();

    /**
     * setUsername
     *
     * @param string $username username
     *
     * @return void
     */
    public function setUsername($username);

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername();

    /**
     * setProperties
     *
     * @param array $properties properties
     *
     * @return void
     */
    public function setProperties($properties);

    /**
     * getProperties
     *
     * @return array
     */
    public function getProperties();

    /**
     * setState
     *
     * @param string $state state
     *
     * @return mixed
     */
    public function setState($state);

    /**
     * getState
     *
     * @return string
     */
    public function getState();

    /**
     * setEmail
     *
     * @param string $email valid email
     *
     * @return void
     */
    public function setEmail($email);

    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail();

    /**
     * setName
     *
     * @param string $name Display name
     *
     * @return void
     */
    public function setName($name);

    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * populate
     *
     * @param User|array $data    data as User or array
     * @param array      $exclude list of object properties to ignore (not populate)
     *
     * @return mixed|void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function populate(
        $data,
        $exclude = []
    );

    /**
     * populateFromObject
     *
     * @param UserInterface $object
     *
     * @return void
     */
    public function populateFromObject(UserInterface $object);

    /**
     * getIterator
     *
     * @return \Traversable
     */
    public function getIterator();

    /**
     * toArray
     *
     * @return array
     */
    public function toArray();
}
