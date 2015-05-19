<?php
/**
 * UserPropertyService.php
 *
 * UserPropertyService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Service;

use
    RcmUser\Event\EventProvider;
use
    RcmUser\Result;
use
    RcmUser\User\Entity\User;

/**
 * Class UserPropertyService
 *
 * UserPropertyService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserPropertyService extends EventProvider
{
    /**
     * getUserProperty
     *
     * @param User   $user              user
     * @param string $propertyNameSpace propertyNameSpace
     * @param null   $dflt              dflt
     * @param bool   $refresh           refresh
     *
     * @return mixed
     */
    public function getUserProperty(
        User $user,
        $propertyNameSpace,
        $dflt = null,
        $refresh = false
    ) {
        $property = $user->getProperty(
            $propertyNameSpace,
            null
        );

        // if a property is not set, see try to get it from an event listener
        if ($property === null || $refresh) {
            // @event getUserProperty.pre -
            $this->getEventManager()->trigger(
                __FUNCTION__,
                $this,
                [
                    'user' => $user,
                    'propertyNameSpace' => $propertyNameSpace
                ]
            );
        }

        $property = $user->getProperty(
            $propertyNameSpace,
            $dflt
        );

        return $property;
    }

    /**
     * populateUserProperty
     * Build a new user property and populate data
     *
     * @param string $propertyNameSpace propertyNameSpace
     * @param mixed  $data              data to populate property
     *
     * @return Result
     */
    public function populateUserProperty(
        $propertyNameSpace,
        $data = []
    ) {
        $results = $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            [
                'propertyNameSpace' => $propertyNameSpace,
                'data' => $data
            ],
            function ($result) {

                if ($result instanceof Result) {
                    return $result->isSuccess();
                }

                return false;
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        return new Result(null, Result::CODE_FAIL, 'No property found to populate.');
    }

    /**
     * getUserPropertyLinks
     * Get a link to an edit page for this user todo - write this
     *
     * @param User   $user              user
     * @param string $propertyNameSpace propertyNameSpace
     *
     * @return mixed
     */
    public function getUserPropertyLinks(
        User $user,
        $propertyNameSpace
    ) {
        $results = $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            [
                'user' => $user,
                'propertyNameSpace' => $propertyNameSpace
            ],
            function ($result) {

                if ($result instanceof Result) {
                    return $result->isSuccess();
                }

                return false;
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        return new Result(null, Result::CODE_FAIL, 'No property link found.');
    }

    /**
     * getUserPropertyIsAllowed
     * Check access for a user to a property
     * If no results returned todo - write this
     *
     * @param User   $user              user
     * @param string $propertyNameSpace propertyNameSpace
     *
     * @return mixed
     */
    public function getUserPropertyIsAllowed(
        User $user,
        $propertyNameSpace
    ) {
        $results = $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            [
                'user' => $user,
                'propertyNameSpace' => $propertyNameSpace
            ],
            function ($result) {

                if ($result instanceof Result) {
                    return $result->isSuccess();
                }

                return false;
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        return new Result(true, Result::CODE_FAIL, 'No Access property found.');
    }
}
