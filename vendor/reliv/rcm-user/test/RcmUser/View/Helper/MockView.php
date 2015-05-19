<?php
/**
 * MockView.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\View\Helper;

use Zend\View\Renderer\RendererInterface;
use Zend\View\Resolver\ResolverInterface;


/**
 * Class MockView
 *
 * MockView
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class MockView implements RendererInterface
{

    public function getEngine()
    {
    }

    public function setResolver(ResolverInterface $resolver)
    {
    }

    public function render($nameOrModel, $values = null)
    {
    }

    public function headScript()
    {
    }

    public function headLink()
    {
    }
}