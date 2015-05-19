<?php
/**
 * TestUserPropertyService.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Service;


use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\User;
use RcmUser\User\Service\UserPropertyService;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserPropertyServiceTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\User\Service\UserPropertyService
 */
class UserPropertyServiceTest extends Zf2TestCase
{

    public $userPropertyService;


    public function getUserPropertyService()
    {
        if (!isset($this->userPropertyService)) {

            $this->buildUserPropertyService();
        }

        return $this->userPropertyService;
    }

    public function buildUserPropertyService()
    {
        $this->userPropertyService = new UserPropertyService();

        $user = new User();
        $user->setId('123');

        //$this->eventManager = new EventManager('test');
        $this->eventManager = $this->getMockBuilder(
            '\Zend\EventManager\EventManagerInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userPropertyService->setEventManager($this->eventManager);

    }

    public function testGetUserProperty()
    {
        $key = 'propertyX';
        $value = 'XXXXX';
        $user = new User();
        $user->setId('123');
        $user->setProperty($key, 'XXXXX');

        $newValue = $this->getUserPropertyService()->getUserProperty(
            $user,
            $key
        );

        $this->assertEquals(
            $value,
            $newValue,
            'Property value did not come back.'
        );
    }

}
 