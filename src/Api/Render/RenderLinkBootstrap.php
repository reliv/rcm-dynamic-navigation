<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\GetRenderServiceConfigOption;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinkBootstrap implements RenderLink
{
    protected $getRenderServiceConfigOption;

    /**
     * @param GetRenderServiceConfigOption $getRenderServiceConfigOption
     */
    public function __construct(
        GetRenderServiceConfigOption $getRenderServiceConfigOption
    ) {
        $this->getRenderServiceConfigOption = $getRenderServiceConfigOption;
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     * @param array                  $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        NavLink $link,
        array $options = []
    ): string
    {
        $target = $link->getTarget();
        $href = $link->getHref();

        // If we have no link set, then we use the defaults
        if (empty($href)) {
            $href = $this->getRenderServiceConfigOption->__invoke(
                $link->getRenderService(),
                'href',
                ''
            );
        }

        $html = "\n" . '<a href="' . $href . '"';

        if ($link->hasLinks()) {
            $html .= 'class="dropdown-toggle'
                . ' ' . self::LINK_CLASS . '"'
                . ' data-toggle="dropdown" role="button" aria-expanded="false"';
        }

        if (!empty($target)) {
            $html .= ' target="' . $target . '"';
        }

        $html .= '>';
        $html .= '<span class="linkText">' . $link->getDisplay() . '</span>';

        if ($link->hasLinks()) {
            $html .= '<span class="caret"></span>';
        }

        $html .= "</a>\n";

        return $html;
    }
}
