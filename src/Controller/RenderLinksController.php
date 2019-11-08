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
use RcmDynamicNavigation\Api\LinksFromData;
use RcmDynamicNavigation\Api\Render\RenderLinks;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksController
{
    protected $requestContext;
    protected $renderLinks;

    public function __construct(
        ContainerInterface $requestContext,
        RenderLinks $renderLinks
    ) {
        $this->requestContext = $requestContext;

        $this->renderLinks = $renderLinks;
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

        $id = $request->getAttribute('id');

        $data = $request->getParsedBody();

        $links = [];

        if (!empty($data['links'])) {
            $links = $data['links'];
        }

        $links = LinksFromData::invoke($links);

        $html = $this->renderLinks->__invoke(
            $request,
            $links,
            [
                \RcmDynamicNavigation\Api\Render\RenderLinks::OPTION_ID => 'RcmDynamicNavigation_' . $id,
            ]
        );

        return new JsonResponse(
            [
                'html' => $html
            ]
        );
    }
}
