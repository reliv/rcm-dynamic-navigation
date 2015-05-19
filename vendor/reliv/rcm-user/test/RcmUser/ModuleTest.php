<?php


namespace RcmUser\Test;

require_once __DIR__ . '/../Zf2TestCase.php';

use RcmUser\Module;
use RcmUser\Test;

/**
 * Class ModuleTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Module
 */
class ModuleTest extends Zf2TestCase
{
    /** @var  \RcmInstanceConfig\Module */
    protected $module;

    public function setUp()
    {
        $this->module = new Module();
    }

    /**
     * No covers tag so this tests both class map file and module file
     */
    public function testGetAutoloaderConfig()
    {
        $autoLoadConfig = $this->module->getAutoloaderConfig();
        $mapPath = array_pop($autoLoadConfig['Zend\Loader\ClassMapAutoloader']);
        $this->assertTrue(is_array(include($mapPath)));
    }

    /**
     * No covers tag so this tests both class map file and module file
     */
    public function testGetConfig()
    {
        $this->assertTrue(is_array($this->module->getConfig()));
    }
} 