<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\GetIsAllowedServicesConfig;
use RcmDynamicNavigation\Api\GetRenderServicesConfig;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminController
{
    protected $isAllowedAdmin;
    protected $getRenderServicesConfig;
    protected $getIsAllowedServicesConfig;

    /**
     * @param IsAllowedAdmin             $isAllowedAdmin
     * @param GetIsAllowedServicesConfig $getIsAllowedServicesConfig
     * @param GetRenderServicesConfig    $getRenderServicesConfig
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        GetIsAllowedServicesConfig $getIsAllowedServicesConfig,
        GetRenderServicesConfig $getRenderServicesConfig
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;

        $this->getIsAllowedServicesConfig = $getIsAllowedServicesConfig;
        $this->getRenderServicesConfig = $getRenderServicesConfig;
    }

    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        if (!$this->isAllowedAdmin->__invoke($request, [])) {
            new JsonResponse(
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
