<?php
/**
 * AdminAclControllerTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Controller;

use RcmUser\Controller\AdminAclController;
use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class AdminAclControllerTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Controller\AdminAclController
 */
class AdminAclControllerTest extends Zf2TestCase
{
    /**
     * @var \RcmUser\Controller\AdminAclController $controller
     */
    public $controller;

    public function setUp()
    {
        $this->mockData = [];

        $this->mockData['isAllowed'] = true;

        $this->mockData['getResponse'] = $this->getMockBuilder(
            '\Zend\Http\Response'
        )
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * buildSuccess
     *
     * @return void
     */
    public function buildSuccess()
    {
        $this->mockData['isAllowed'] = true;

        $this->controller = new WrapperAdminAclController($this->mockData);
    }

    /**
     * buildFail
     *
     * @return void
     */
    public function buildFail()
    {
        $this->mockData['isAllowed'] = false;

        $this->controller = new WrapperAdminAclController($this->mockData);
    }

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $this->buildSuccess();

        $result = $this->controller->indexAction();

        $this->assertInstanceOf(
            '\Zend\View\Model\ViewModel',
            $result
        );

        $this->buildFail();

        $result = $this->controller->indexAction();

        $this->assertInstanceOf(
            '\Zend\Http\Response',
            $result
        );
    }
}

/**
 * Class WrapperAdminAclController
 *
 * WRAPPER
 */
class WrapperAdminAclController extends AdminAclController
{

    public $mocks = [];

    public function __construct($mocks = [])
    {
        $this->mocks = $mocks;
    }

    public function isAllowed(
        $resourceId = RcmUserAclResourceProvider::RESOURCE_ID_ROOT,
        $privilege = null
    ) {
        return $this->mocks['isAllowed'];
    }

    public function getResponse()
    {
        return $this->mocks['getResponse'];
    }

}
 