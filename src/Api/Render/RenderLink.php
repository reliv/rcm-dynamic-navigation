<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RenderLink
{
    const LINK_CLASS = 'menu-link';
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
    ): string;
}
