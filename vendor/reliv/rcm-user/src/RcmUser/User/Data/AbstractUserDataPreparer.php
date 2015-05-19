<?php
/**
 * AbstractUserDataPreparer.php
 *
 * AbstractUserDataPreparer
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\User\Data;

use
    RcmUser\User\Entity\User;
use
    RcmUser\User\Result;

/**
 * Class AbstractUserDataPreparer
 *
 * AbstractUserDataPreparer
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AbstractUserDataPreparer implements UserDataPreparerInterface
{
    /**
     * prepareUserCreate
     *
     * @param User $requestUser  requestUser
     * @param User $responseUser responseUser
     *
     * @return Result
     */
    public function prepareUserCreate(
        User $requestUser,
        User $responseUser
    ) {
        return new Result($responseUser);
    }

    /**
     * prepareUserUpdate
     *
     * @param User $requestUser  requestUser
     * @param User $responseUser responseUser
     * @param User $existingUser existingUser
     *
     * @return Result
     */
    public function prepareUserUpdate(
        User $requestUser,
        User $responseUser,
        User $existingUser
    ) {
        return new Result($responseUser);
    }
}
