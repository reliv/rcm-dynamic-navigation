<?php

namespace RcmDynamicNavigation\View;

use RcmDynamicNavigation\Model\NavLink;
use Zend\Diactoros\ServerRequestFactory;
use Zend\View\Helper\AbstractHelper;

/**
 * Render links
 * Render a collection of NavLinks
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RenderLinks extends AbstractHelper
{
    /**
     * @var \RcmDynamicNavigation\Api\Render\RenderLinks
     */
    protected $renderLinks;

    /**
     * @param \RcmDynamicNavigation\Api\Render\RenderLinks $renderLinks
     */
    public function __construct(
        \RcmDynamicNavigation\Api\Render\RenderLinks $renderLinks
    ) {
        $this->renderLinks = $renderLinks;
    }

    /**
     * Render the links
     *
     * @param array   $links Array of NavLinks
     * @param boolean $adminMode Render in admin mode
     * @param string  $id    Id to pass to container
     *
     * @return string
     */
    public function __invoke($links, $adminMode, $id)
    {
        $request = ServerRequestFactory::fromGlobals();

        return $this->renderLinks->__invoke(
            $request,
            $links,
            [
                \RcmDynamicNavigation\Api\Render\RenderLinks::OPTION_ADMIN_MODE => $adminMode,
                \RcmDynamicNavigation\Api\Render\RenderLinks::OPTION_ID => $id,
            ]
        );
    }
}
