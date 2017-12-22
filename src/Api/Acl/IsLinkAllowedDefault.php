<?php

namespace RcmDynamicNavigation\Api\Acl;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\GetIsAllowedServiceConfig;
use RcmDynamicNavigation\Api\Options;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsLinkAllowedDefault implements IsLinkAllowed
{
    protected $serviceContainer;
    protected $getIsAllowedServiceConfig;

    /**
     * @param ContainerInterface        $serviceContainer
     * @param GetIsAllowedServiceConfig $getIsAllowedServiceConfig
     */
    public function __construct(
        $serviceContainer,
        GetIsAllowedServiceConfig $getIsAllowedServiceConfig
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->getIsAllowedServiceConfig = $getIsAllowedServiceConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     *
     * @return bool
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        NavLink $link
    ): bool {
        $isAllowedServiceAlias = $link->getIsAllowedService();

        $isAllowedServiceConfig = $this->getIsAllowedServiceConfig->__invoke(
            $isAllowedServiceAlias
        );

        if (empty($isAllowedServiceConfig)) {
            $isAllowedServiceConfig = $this->getIsAllowedServiceConfig->__invoke(
                'default'
            );
        }

        $isAllowedServiceName = Options::getRequired(
            $isAllowedServiceConfig,
            'service'
        );

        $isAllowedServiceOptions = Options::get(
            $isAllowedServiceConfig,
            'options',
            []
        );

        $linkIsAllowedOptions = Options::get(
            $link->getOptions(),
            $isAllowedServiceAlias,
            []
        );

        // over-ride defaults if set in link
        foreach ($isAllowedServiceOptions as $key => $value) {
            if (array_key_exists($key, $linkIsAllowedOptions)) {
                $isAllowedServiceOptions[$key] = $linkIsAllowedOptions[$key];
            }
        }

        /** @var IsAllowed $isAllowedService */
        $isAllowedService = $this->serviceContainer->get($isAllowedServiceName);

        return $isAllowedService->__invoke(
            $request,
            $isAllowedServiceOptions
        );
    }
}
