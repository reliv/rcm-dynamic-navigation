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
    ): string {
        $depth = Options::get(
            $options,
            self::OPTION_DEPTH,
            self::DEFAULT_DEPTH
        );

        $id = Options::getRequired(
            $options,
            self::OPTION_ID
        );

        return $this->render(
            $request,
            $links,
            $depth,
            $id
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink[]              $links Array of NavLinks
     * @param int                    $depth
     * @param string                 $id    Id to pass to container
     *
     * @return string
     */
    public function render(
        ServerRequestInterface $request,
        $links,
        $depth,
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
            . $this->getUl($request, $links, $depth, $id) . '
                </div>
            </nav>
        ';

        return $navHtml;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $links Array of NavLinks
     * @param int                    $depth
     * @param string                 $id    Id to pass to container
     *
     * @return string
     */
    protected function getUl(
        ServerRequestInterface $request,
        $links,
        $depth,
        $id = null
    ) {
        $html = '';

        $menuClass = self::MENU_CLASS . ' ' . self::DEPTH_CLASS . $depth;

        if (!empty($id)) {
            $html .= "\n" . '<ul class="nav navbar-nav ' . $menuClass . '">';
        } else {
            $html .= "\n" . '<ul class="dropdown-menu ' . $menuClass . '" role="menu">';
        }

        foreach ($links as $link) {
            $html .= $this->getLi($request, $link, $depth);
        }

        $html .= "</ul>\n";

        return $html;
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     * @param boolean                $depth
     *
     * @return string
     */
    protected function getLi(
        ServerRequestInterface $request,
        NavLink $link,
        $depth
    ) {
        $objectClass = $link->getClass();

        // We always use the default system classes
        $systemClass = $this->getRenderServiceConfigOption->__invoke(
            $link->getRenderService(),
            'class',
            ''
        );

        $systemClass = $systemClass . ' ' . self::ITEM_CLASS;

        if ($link->hasLinks()) {
            $objectClass .= ' dropdown';
        }

        $html = "\n<li";

        if (!empty($objectClass) || !empty($systemClass)) {
            $html .= ' class="' . $objectClass . ' ' . $systemClass . '"';
        }

        $html .= ' id="' . $link->getId() . '"';

        $html .= '>' . "\n";

        $html .= $this->renderLinkOption->__invoke(
            $request,
            $link
        );

        if ($link->hasLinks()) {
            $depth++;
            $html .= $this->getUl($request, $link->getLinks(), $depth);
        }

        $html .= "</li>\n";

        return $html;
    }
}
