<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\GetRenderServiceConfig;
use RcmDynamicNavigation\Api\Options;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinkOption implements RenderLink
{
    protected $serviceContainer;
    protected $getRenderServiceConfig;

    /**
     * @param ContainerInterface     $serviceContainer
     * @param GetRenderServiceConfig $getRenderServiceConfig
     */
    public function __construct(
        $serviceContainer,
        GetRenderServiceConfig $getRenderServiceConfig
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->getRenderServiceConfig = $getRenderServiceConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     * @param array                  $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        NavLink $link,
        array $options = []
    ): string
    {
        $renderServiceAlias = $link->getRenderService();

        $renderServiceConfig = $this->getRenderServiceConfig->__invoke(
            $renderServiceAlias
        );

        if (empty($renderServiceConfig)) {
            $renderServiceConfig = $this->getRenderServiceConfig->__invoke(
                'default'
            );
        }

        $renderServiceName = Options::getRequired(
            $renderServiceConfig,
            'service'
        );

        $renderServiceOptions = Options::get(
            $renderServiceConfig,
            'options',
            []
        );

        $linkRenderOptions = Options::get(
            $link->getOptions(),
            $renderServiceAlias,
            []
        );

        // over-ride defaults if set in link
        foreach ($renderServiceOptions as $key => $value) {
            if (array_key_exists($key, $linkRenderOptions)) {
                $renderServiceOptions[$key] = $linkRenderOptions[$key];
            }
        }

        /** @var RenderLink $renderService */
        $renderService = $this->serviceContainer->get($renderServiceName);

        return $renderService->__invoke(
            $request,
            $link,
            $renderServiceOptions
        );
    }

    /**
     * @return mixed
     */
    protected function getRenderServicesConfig()
    {
        return $this->config['rcmPlugin']['RcmDynamicNavigation']['renderServices'];
    }
}
