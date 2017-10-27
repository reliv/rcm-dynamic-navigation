<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\GetRenderServiceConfigOption;
use RcmDynamicNavigation\Api\Options;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksBootstrap implements RenderLinks
{
    protected $getRenderServiceConfigOption;
    protected $renderLinkOption;

    /**
     * @param GetRenderServiceConfigOption $getRenderServiceConfigOption
     * @param RenderLinkOption             $renderLinkOption
     */
    public function __construct(
        GetRenderServiceConfigOption $getRenderServiceConfigOption,
        RenderLinkOption $renderLinkOption
    ) {
        $this->getRenderServiceConfigOption = $getRenderServiceConfigOption;
        $this->renderLinkOption = $renderLinkOption;
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink[]              $links
     * @param array                  $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $links,
        array $options = []
    ): string
    {
        $adminMode = (array_key_exists(self::OPTION_ADMIN_MODE, $options) ? $options[self::OPTION_ADMIN_MODE] : false);

        if (!array_key_exists(self::OPTION_ID, $options)) {
            throw new \Exception('Option is required: ' . self::OPTION_ID);
        }

        $id = $options[self::OPTION_ID];

        return $this->render(
            $request,
            $links,
            $adminMode,
            $id
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink[]              $links     Array of NavLinks
     * @param boolean                $adminMode Render in admin mode
     * @param string                 $id        Id to pass to container
     *
     * @return string
     */
    public function render(
        ServerRequestInterface $request,
        $links,
        $adminMode,
        $id
    ) {
        $navHtml
            = '
            <nav class="navbar navbar-default">
                <div class="navbar-header">
                    <button
                    type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#' . $id . '"
                    aria-expanded="false"
                    aria-controls="navbar"
                    >
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="' . $id . '" class="navbar-collapse collapse">'
                    . $this->getUl($request, $links, $adminMode, $id) . '
                </div>
            </nav>
        ';

        return $navHtml;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $links     Array of NavLinks
     * @param boolean                $adminMode Render in admin mode
     * @param string                 $id        Id to pass to container
     *
     * @return string
     */
    protected function getUl(
        ServerRequestInterface $request,
        $links,
        $adminMode,
        $id = null
    ) {
        $html = '';

        if (!empty($id)) {
            $html .= '<ul class="nav navbar-nav">';
        } else {
            $html .= '<ul class="dropdown-menu" role="menu">';
        }

        foreach ($links as $link) {
            $html .= $this->getLi($request, $link, $adminMode);
        }

        $html .= '</ul>' . "\n";

        return $html;
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     * @param boolean                $adminMode
     *
     * @return string
     */
    protected function getLi(
        ServerRequestInterface $request,
        NavLink $link,
        $adminMode
    ) {
        $objectClass = $link->getClass();

        // We always use the default system classes
        $systemClass = $this->getRenderServiceConfigOption->__invoke(
            $link->getRenderService(),
            'systemClass',
            ''
        );

        if ($link->hasLinks()) {
            $objectClass .= ' dropdown';
        }

        $html = '<li';

        if (!empty($objectClass) || !empty($systemClass)) {
            $html .= ' class="' . $objectClass . ' ' . $systemClass . '"';
        }

        if ($adminMode) {
            //$html .= ' data-permissions="' . implode(',', $link->getPermissions()) . '"';

            $html .= ' data-is-allowed-options="' . htmlentities(json_encode($link->getIsAllowedServiceOptions()), ENT_QUOTES, 'UTF-8'). '"';
            $html .= ' data-render-options="' . htmlentities(json_encode($link->getRenderServiceOptions()), ENT_QUOTES, 'UTF-8'). '"';
        }

        $html .= '>' . "\n";

        $html .= $this->renderLinkOption->__invoke(
            $request,
            $link
        );

        if ($link->hasLinks()) {
            $html .= $this->getUl($request, $link->getLinks(), $adminMode);
        }

        $html .= '</li>' . "\n";

        return $html;
    }

    /**
     * @return string
     */
    protected function getMobileMenu()
    {
        $html = '<div class="glyphicon glyphicon-menu-hamburger mobileMenuIcon" aria-hidden="true"></div>';

        return $html;
    }
}
