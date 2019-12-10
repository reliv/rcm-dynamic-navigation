<?php

namespace RcmDynamicNavigation\Api;

use Psr\Http\Message\ServerRequestInterface;
use Pws\Service\PwsService;
use RcmUser\Api\Authentication\GetIdentity;

class IsPwsOwner
{
    /**
     * @var GetIdentity
     */
    protected $getIdentity;

    /**
     * @var PwsService
     */
    protected $pwsService;


    public function __construct(
        GetIdentity $getIdentity,
        PwsService $pwsService
    ) {
        $this->getIdentity = $getIdentity;
        $this->pwsService = $pwsService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        $identity = $this->getIdentity->__invoke($request);
        if (!$identity) {
            return false;
        }
        $rcn = $identity->getId();
        if (!$this->pwsService->isPwsOwner($rcn)) {
            return false;
        }
        return true;
    }
}
