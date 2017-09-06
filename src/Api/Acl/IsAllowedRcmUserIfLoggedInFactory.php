<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserIfLoggedInFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedRcmUserIfLoggedIn
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedRcmUserIfLoggedIn(
            $serviceContainer->get(RcmUserService::class)
        );
    }
}
