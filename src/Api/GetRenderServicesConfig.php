<?php

namespace RcmDynamicNavigation\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRenderServicesConfig
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

    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->servicesConfig;
    }
}
