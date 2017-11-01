<?php
/**
 * Test for NavLink Data Model
 *
 * Test for NavLink Data Model
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace RcmDynamicNavigation\Test;

use RcmDynamicNavigation\Model\NavLink;

require_once __DIR__ . '/../autoload.php';

/**
 * Test for NavLink Data Model
 *
 * Test for NavLink Data Model
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class NavLinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDynamicNavigation\Model\NavLink */
    protected $link;

    /**
     * Setup for all tests
     *
     * @return void
     */
    public function setup()
    {
        $this->link = new NavLink('test', 'Test', '#');
    }

    /**
     * Get a config array for tests
     *
     * @param string $display Display
     * @param string $class   Class
     * @param string $href    Href
     * @param string $target  Target
     * @param string $options Options
     *
     * @return NavLink
     */
    public function buildUnit(
        $display,
        $class = 'testClass',
        $href = '/test-page',
        $target = '_SELF',
        $options = []
    ) {
        return new NavLink(
            'test',
            $display,
            $href,
            $target,
            [],
            $class,
            'default',
            'default',
            $options
        );
    }

    /**
     * Test the constructor
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('\RcmDynamicNavigation\Model\NavLink', $this->link);
    }

    /**
     * Test the constructor with data
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::__construct
     */
    public function testConstructorWithDefaultInstanceConfig()
    {
        $link = $this->buildUnit('Test Link');
        $this->assertInstanceOf('\RcmDynamicNavigation\Model\NavLink', $link);
    }

    /**
     * Test Set And Get Href
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getHref
     * @covers \RcmDynamicNavigation\Model\NavLink::setHref
     */
    public function testSetAndGetHref()
    {
        $href = '/somewhere';
        $this->link->setHref($href);
        $this->assertEquals($href, $this->link->getHref());
    }

    /**
     * Test Add And Get Class
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addClass
     * @covers \RcmDynamicNavigation\Model\NavLink::getClass
     */
    public function testAddAndGetClass()
    {
        $class = 'SomeClass';
        $this->link->addClass($class);
        $this->assertEquals($class, $this->link->getClass());
    }

    /**
     * Test Set And Get Multiple Classes
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::setClass
     * @covers \RcmDynamicNavigation\Model\NavLink::getClass
     */
    public function testSetAndGetMultipleClasses()
    {
        $class = 'SomeClass AnotherClass YetAnotherClass';
        $this->link->setClass($class);
        $this->assertEquals($class, $this->link->getClass());
    }

    /**
     * Test Set And Get Target
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getTarget
     * @covers \RcmDynamicNavigation\Model\NavLink::setTarget
     */
    public function testSetAndGetTarget()
    {
        $target = '_blank';
        $this->link->setTarget($target);
        $this->assertEquals($target, $this->link->getTarget());
    }

    /**
     * Test Set And Get Display
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getDisplay
     * @covers \RcmDynamicNavigation\Model\NavLink::setDisplay
     */
    public function testSetAndGetDisplay()
    {
        $display = 'Some words go here';
        $this->link->setDisplay($display);
        $this->assertEquals($display, $this->link->getDisplay());
    }

    /**
     * Test Set And Get Options
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getOptions
     * @covers \RcmDynamicNavigation\Model\NavLink::setOptions
     */
    public function testSetAndGetOptions()
    {
        $options = array(
            'user1',
            'user2',
            'user3'
        );

        $this->link->setOptions($options);

        $result = $this->link->getOptions();

        $this->assertCount(3, $result);
        $this->assertEquals($options, $result);
    }

    /**
     * Test Set And Get Options From Csv
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::getOptions
     * @covers \RcmDynamicNavigation\Model\NavLink::setOptions
     */
    public function testSetAndGetOptionsFromCsv()
    {
        $options = array(
            'user1',
            'user2',
            'user3'
        );

        $this->link->setOptions($options);

        $result = $this->link->getOptions();

        $this->assertCount(3, $result);
        $this->assertEquals($options, $result);
    }

    /**
     * Test Add And Get Links
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addLink
     * @covers \RcmDynamicNavigation\Model\NavLink::getLinks
     */
    public function testAddAndGetLinks()
    {
        $display = 'SubLink';
        $linkToAdd = $this->buildUnit('NOPE Display');
        $linkToAdd->setDisplay($display);
        $this->link->addLink($linkToAdd);

        $result = $this->link->getLinks();
        $this->assertCount(1, $result);

        /** @var NavLink $resultSublink */
        $resultSublink = array_pop($result);

        $this->assertInstanceOf(\RcmDynamicNavigation\Model\NavLink::class, $resultSublink);

        $this->assertEquals($display, $resultSublink->getDisplay());
    }

    /**
     * Test Add And Get Links From Data Array
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::addLink
     * @covers \RcmDynamicNavigation\Model\NavLink::getLinks
     */
    public function testAddAndGetLinksFromDataArray()
    {
        $display = 'SubLink';
        $sublink = $this->buildUnit($display);
        $this->link->addLink($sublink);

        $result = $this->link->getLinks();
        $this->assertCount(1, $result);

        /** @var NavLink $resultSublink */
        $resultSublink = array_pop($result);

        $this->assertInstanceOf(\RcmDynamicNavigation\Model\NavLink::class, $resultSublink);

        $this->assertEquals($display, $resultSublink->getDisplay());
    }

    /**
     * Test Set And Get Links
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::setLinks
     * @covers \RcmDynamicNavigation\Model\NavLink::getLinks
     */
    public function testSetAndGetLinks()
    {
        $linkOne = $this->buildUnit('Display 1');
        $linkTwo = $this->buildUnit('Display 2');
        $linkThree = $this->buildUnit('Display 3');

        $linkArray = array($linkOne, $linkTwo, $linkThree);

        $this->link->setLinks($linkArray);

        $result = $this->link->getLinks();

        $this->assertEquals($linkArray, $result);
    }

    /**
     * Test Has Links
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::hasLinks
     */
    public function testHasLinks()
    {
        $linkOne = $this->buildUnit('Display 1');
        $linkTwo = $this->buildUnit('Display 2');
        $linkThree = $this->buildUnit('Display 3');

        $linkArray = array($linkOne, $linkTwo, $linkThree);

        $this->link->setLinks($linkArray);

        $this->assertTrue($this->link->hasLinks());
    }

    /**
     * Test Has Links False
     *
     * @return void
     * @covers \RcmDynamicNavigation\Model\NavLink::hasLinks
     */
    public function testHasLinksFalse()
    {
        $this->assertFalse($this->link->hasLinks());
    }
}
