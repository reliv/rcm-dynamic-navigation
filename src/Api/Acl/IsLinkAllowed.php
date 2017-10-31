<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsLinkAllowed
{
    /**
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        NavLink $link
    ):bool;
}
