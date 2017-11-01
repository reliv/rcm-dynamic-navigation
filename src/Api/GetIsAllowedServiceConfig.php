<?php

namespace RcmDynamicNavigation\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetIsAllowedServiceConfig
{
    protected $servicesConfig;

    /**
     * @param array $servicesConfig
     */
    public function __construct(
        array $servicesConfig
    ) {
        $this->servicesConfig = $servicesConfig;
    }

    public function __invoke(
        string $serviceAlias
    ) {
        return Options::get(
            $this->servicesConfig,
            $serviceAlias,
            []
        );
    }
}
