<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;
use Rcm\Api\GetSiteByRequest;
use Rcm\Entity\Site;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserSiteAdmin implements IsAllowedAdmin
{
    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * @var GetSiteByRequest
     */
    protected $getSiteByRequest;

    /**
     * @var ResourceName
     */
    protected $resourceName;

    /**
     * @param RcmUserService   $rcmUserService
     * @param GetSiteByRequest $getSiteByRequest
     * @param ResourceName     $resourceName
     */
    public function __construct(
        RcmUserService $rcmUserService,
        GetSiteByRequest $getSiteByRequest,
        ResourceName $resourceName
    ) {
        $this->rcmUserService = $rcmUserService;
        $this->getSiteByRequest = $getSiteByRequest;
        $this->resourceName = $resourceName;
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
        /** @var Site $site */
        $site = $this->getSiteByRequest->__invoke(
            $request
        );

        if (empty($site)) {
            return false;
        }

        return $this->rcmUserService->isAllowed(
            $this->resourceName->get(
                ResourceName::RESOURCE_SITES,
                $site->getSiteId()
            ),
            'admin'
        );
    }
}
