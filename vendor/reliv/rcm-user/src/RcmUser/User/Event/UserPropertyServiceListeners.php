<?php
/**
 * UserPropertyServiceListeners.php
 *
 * UserPropertyServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Event;

use
    RcmUser\Result;
use
    RcmUser\User\Entity\UserRoleProperty;

/**
 * UserPropertyServiceListeners
 *
 * UserPropertyServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserPropertyServiceListeners extends AbstractUserDataServiceListeners
{
    /**
     * @var int $priority
     */
    protected $priority = 1;
    /**
     * @var array $listenerMethods
     */
    protected $listenerMethods
        = [
            //'onGetUserPropertyLinks' => 'getUserPropertyLinks',
            'onPopulateUserProperty' => 'populateUserProperty',
        ];

    /**
     * getUserPropertyKey
     *
     * @return string
     */
    public function getUserPropertyKey()
    {
        return UserRoleProperty::PROPERTY_KEY;
    }

    /**
     * onPopulateUserProperty
     *
     * @param Event $e e
     *
     * @return bool|Result
     */
    public function onPopulateUserProperty($e)
    {
        $propertyNameSpace = $e->getParam('propertyNameSpace');
        $data = $e->getParam('data');
        $thisPropertyNameSpace = $this->getUserPropertyKey();

        if ($propertyNameSpace !== $thisPropertyNameSpace) {
            return false;
        }

        $property = new UserRoleProperty();

        try {

            $property->populate($data);
        } catch (\Exception $e) {
            return new \RcmUser\Result(
                $property,
                Result::CODE_FAIL,
                'Property failed to populate with error: ' . $e->getMessage()
            );
        }

        return new Result($property);
    }

    /**
     * onGetUserPropertyLinks @todo
     *
     * @param Event $e e
     *
     * @return \RcmUser\Result
     *
     * public function onGetUserPropertyLinks($e)
     * {
     * $user = $e->getParam('user');
     * $propertyNameSpace = $e->getParam('propertyNameSpace');
     * $thisPropertyNameSpace = $this->getUserPropertyKey();
     *
     * if ($propertyNameSpace !== $thisPropertyNameSpace) {
     *
     * return false;
     * }
     *
     * $links = new Links();
     * $link = new Link();
     * $link->setTitle('Edit User Roles');
     * $link->setType('edit');
     * $link->setHelp('Edit page for adding removing user roles.');
     * $link->setUrl('/admin/' . $user->getId());
     * $links->addLink($link);
     *
     * return new Result($links);
     * }
     */

    /**
     * onGetUserPropertyIsAllowed @todo
     *
     * @param Event $e e
     *
     * @return bool
     *
     * public function onGetUserPropertyIsAllowed($e)
     * {
     * $user = $e->getParam('user');
     * $propertyNameSpace = $e->getParam('propertyNameSpace');
     * $thisPropertyNameSpace = $this->getUserPropertyKey();
     *
     * if ($propertyNameSpace !== $thisPropertyNameSpace) {
     *
     * return false;
     * }
     *
     * return false;
     * }
     */
}
