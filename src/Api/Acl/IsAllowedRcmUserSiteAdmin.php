<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;
use Rcm\Api\GetSiteByRequest;
use Rcm\Entity\Site;
use RcmUser\Api\Acl\IsAllowed;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserSiteAdmin implements IsAllowedAdmin
{
    const DEFAULT_PRIVILEGE = 'admin';

    /**
     * @var IsAllowed
     */
    protected $isAllowed;

    /**
     * @var GetSiteByRequest
     */
    protected $getSiteByRequest;

    /**
     * @var ResourceName
     */
    protected $resourceName;

    /**
     * @var string
     */
    protected $privilege = self::DEFAULT_PRIVILEGE;

    /**
     * @param IsAllowed        $isAllowed
     * @param GetSiteByRequest $getSiteByRequest
     * @param ResourceName     $resourceName
     * @param string           $privilege
     */
    public function __construct(
        IsAllowed $isAllowed,
        GetSiteByRequest $getSiteByRequest,
        ResourceName $resourceName,
        string $privilege = self::DEFAULT_PRIVILEGE
    ) {
        $this->isAllowed = $isAllowed;
        $this->getSiteByRequest = $getSiteByRequest;
        $this->resourceName = $resourceName;
        $this->privilege = $privilege;
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
    ): bool
    {
        /** @var Site $site */
        $site = $this->getSiteByRequest->__invoke(
            $request
        );

        if (empty($site)) {
            return false;
        }

        return $this->isAllowed->__invoke(
            $request,
            $this->resourceName->get(
                ResourceName::RESOURCE_SITES,
                $site->getSiteId()
            ),
            $this->privilege
        );
    }
}
