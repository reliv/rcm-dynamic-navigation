<?php
/**
 * UserSession.php
 *
 * UserSession
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Storage
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Authentication\Storage;

use
    Zend\Authentication\Storage\Session;

/**
 * UserSession
 *
 * UserSession
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Storage
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserSession extends Session
{

    /**
     * __construct
     *
     * @param string         $namespace namespace
     * @param string         $member    member
     * @param SessionManager $manager   manager
     */
    public function __construct(
        $namespace = 'RcmUser',
        $member = 'user',
        SessionManager $manager = null
    ) {
        parent::__construct(
            $namespace,
            $member,
            $manager
        );
    }
}
