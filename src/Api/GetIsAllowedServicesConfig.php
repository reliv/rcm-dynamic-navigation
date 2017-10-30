<?php

namespace RcmDynamicNavigation\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetIsAllowedServicesConfig
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

    public function __invoke() {
        return $this->servicesConfig;
    }
}
