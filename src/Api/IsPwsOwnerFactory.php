<?php

namespace RcmDynamicNavigation\Api;

use Pws\Service\PwsService;
use RcmUser\Api\Authentication\GetIdentity;

class IsPwsOwnerFactory
{
    public function __invoke($serviceContainer)
    {
        return new IsPwsOwner(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(PwsService::class)
        );
    }
}
