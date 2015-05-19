<?php
/**
 * UserPropertyInterface.php
 *
 * UserPropertyInterface
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
 * Interface UserPropertyInterface
 *
 * UserPropertyInterface
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

interface UserPropertyInterface extends \JsonSerializable
{

    /**
     * populate
     *
     * @param array|UserPropertyInterface $data data to populate with
     *
     * @return mixed
     */
    public function populate($data = []);
}
