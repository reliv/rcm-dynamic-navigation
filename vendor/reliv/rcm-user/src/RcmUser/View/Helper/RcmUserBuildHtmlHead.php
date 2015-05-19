<?php
/**
 * RcmUserBuildHtmlHead.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\View\Helper;

use
    Zend\View\Helper\AbstractHelper;

/**
 * Class RcmUserBuildHtmlHead
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserBuildHtmlHead extends AbstractHelper
{
    /**
     * @var array|null
     */
    public $htmlAssets = null;

    public $view;

    /**
     * @param array|null $htmlAssets
     */
    public function __construct($htmlAssets = null)
    {
        $this->htmlAssets = $htmlAssets;
        $this->view = $this->getView();
    }

    /**
     * buildHtmlHead
     *
     * @return void
     */
    public function buildHtmlHead()
    {
        if (is_array($this->htmlAssets)) {

            foreach ($this->htmlAssets as $type => $paths) {

                foreach ($paths as $path) {
                    $this->includeHead(
                        $type,
                        $path
                    );
                }

            }

            $this->htmlAssets = null;
        }
    }

    /**
     * includeHead
     *
     * @param $type
     * @param $path
     *
     * @return void
     */
    function includeHead(
        $type,
        $path
    ) {
        switch ($type) {
            case 'css':
                $this->view->headLink()->appendStylesheet($path);
                break;
            case 'js':
                $this->view->headScript()->appendFile($path);
                break;
        }
    }

    /**
     * __invoke
     *
     * @param array $options options
     *
     * @return mixed
     */
    public function __invoke(
        $options = []
    ) {
        $this->buildHtmlHead();
    }
}
