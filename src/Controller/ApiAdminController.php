<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl2\SecurityPropertyConstants;
use RcmDynamicNavigation\Api\Acl\requestContext;
use RcmDynamicNavigation\Api\GetIsAllowedServicesConfig;
use RcmDynamicNavigation\Api\GetRenderServicesConfig;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminController
{
    protected $requestContext;
    protected $getRenderServicesConfig;
    protected $getIsAllowedServicesConfig;

    public function __construct(
        ContainerInterface $requestContext,
        GetIsAllowedServicesConfig $getIsAllowedServicesConfig,
        GetRenderServicesConfig $getRenderServicesConfig
    ) {
        $this->requestContext = $requestContext;

        $this->getIsAllowedServicesConfig = $getIsAllowedServicesConfig;
        $this->getRenderServicesConfig = $getRenderServicesConfig;
    }

    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->requestContext->get(AssertIsAllowed::class);

        try {
            $assertIsAllowed->__invoke(AclActions::READ, ['type' => SecurityPropertyConstants::TYPE_ADMIN_TOOL]);
        } catch (NotAllowedException $e) {
            return new JsonResponse(
                null,
                401
            );
        }

        return new JsonResponse(
            [
                'isAllowedServices' => $this->getIsAllowedServicesConfig->__invoke(),
                'renderServices' => $this->getRenderServicesConfig->__invoke(),
            ]
        );
    }
}
