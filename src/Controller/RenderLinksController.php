<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\LinksFromData;
use RcmDynamicNavigation\Api\Render\RenderLinks;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksController
{
    protected $isAllowedAdmin;
    protected $renderLinks;

    /**
     * @param IsAllowedAdmin $isAllowedAdmin
     * @param RenderLinks    $renderLinks
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        RenderLinks $renderLinks
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;

        $this->renderLinks = $renderLinks;
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
                \RcmDynamicNavigation\Api\Render\RenderLinks::OPTION_ID => 'RcmDynamicNavigation_TEMP',
            ]
        );

        return new JsonResponse(
            [
                'html' => $html
            ]
        );
    }
}
