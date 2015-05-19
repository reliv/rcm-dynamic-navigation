<?php
/**
 * AbstractAdminControllerTest.php
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

use RcmUser\Controller\AbstractAdminController;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class AbstractAdminControllerTest
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
 * @covers    \RcmUser\Controller\AbstractAdminController
 */
class AbstractAdminControllerTest extends Zf2TestCase
{
    /**
     * @var \RcmUser\Controller\AbstractAdminController $controller
     */
    public $controller;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->mocks = [];

        $this->mocks['rcmUserIsAllowed'] = true;

        $this->mocks['getResponse'] = $this->getMockBuilder(
            '\Zend\Http\Response'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new WrapperAbstractAdminController($this->mocks);

        //parent::setUp();
    }

    public function test()
    {

        $result = $this->controller->isAllowed(
            'someresourceid',
            'someprivliege'
        );
        $this->assertTrue($result);

        $result = $this->controller->getNotAllowedResponse();
        $this->assertInstanceOf(
            '\Zend\Http\Response',
            $result
        );

        $result = $this->controller->buildView();

        $this->assertInstanceOf(
            '\Zend\View\Model\ViewModel',
            $result
        );
    }
}

/**
 * Class WrapperAbstractAdminController
 *
 * WRAPPER
 */
class WrapperAbstractAdminController extends AbstractAdminController
{

    public $mockData = [];

    public function __construct($mockData = [])
    {
        $this->mockData = $mockData;
    }

    public function rcmUserIsAllowed()
    {
        return $this->mockData['rcmUserIsAllowed'];
    }

    public function getResponse()
    {
        return $this->mockData['getResponse'];
    }

    public function buildView($viewArr = [])
    {

        return parent::buildView($viewArr);
    }
}
 