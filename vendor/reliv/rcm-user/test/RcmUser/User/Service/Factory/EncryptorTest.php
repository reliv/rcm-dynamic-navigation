<?php
/**
 * EncryptorTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Service\Factory;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Service\Factory\Encryptor;

require_once __DIR__ . '/../../../../Zf2TestCase.php';

/**
 * Class EncryptorTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Service\Factory\Encryptor
 */
class EncryptorTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new Encryptor();

        $service = $factory->createService($this->getMockServiceLocator());
        $this->assertInstanceOf(
            '\Zend\Crypt\Password\PasswordInterface',
            $service
        );
        //
    }
}
 