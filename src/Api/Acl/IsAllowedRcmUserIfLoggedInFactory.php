<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use RcmUser\Api\Authentication\GetIdentity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserIfLoggedInFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedRcmUserIfLoggedIn
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedRcmUserIfLoggedIn(
            $serviceContainer->get(GetIdentity::class)
        );
    }
}
