<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetIdentity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserIfNotLoggedIn implements IsAllowed
{
    /**
     * @var GetIdentity
     */
    protected $getIdentity;

    /**
     * @param GetIdentity $getIdentity
     */
    public function __construct(
        GetIdentity $getIdentity
    ) {
        $this->getIdentity = $getIdentity;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        $currentUser = $this->getIdentity->__invoke(
            $request
        );

        return empty($currentUser);
    }
}
