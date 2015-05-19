<?php
/**
 * AuthenticationService.php
 *
 * AuthenticationService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service;
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Authentication\Service;

use
    RcmUser\User\Entity\User;

/**
 * AuthenticationService
 *
 * AuthenticationService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AuthenticationService extends \Zend\Authentication\AuthenticationService
{

    /**
     * setIdentity - User to refresh existing session
     *
     * @param User $identity identity
     *
     * @return mixed|null
     */
    public function setIdentity(User $identity)
    {
        $storage = $this->getStorage();

        return $storage->write($identity);
    }
}
