<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedAnyFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedAny
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedAny();
    }
}
