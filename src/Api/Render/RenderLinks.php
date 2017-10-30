<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RenderLinks
{
    const OPTION_ADMIN_MODE = 'adminMode';
    const OPTION_DEPTH = 'depth';
    const OPTION_ID = 'id';

    const DEFAULT_DEPTH = 0;

    const MENU_CLASS = 'menu';
    const DEPTH_CLASS = 'depth-';
    const ITEM_CLASS = 'menu-item';

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
    ): string;
}
