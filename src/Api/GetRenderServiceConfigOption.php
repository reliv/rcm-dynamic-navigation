<?php

namespace RcmDynamicNavigation\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRenderServiceConfigOption
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
     * @param string $serviceAlias
     * @param        $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public function __invoke(
        string $serviceAlias,
        $key,
        $default = null
    ) {
        $serviceConfig = Options::get(
            $this->servicesConfig,
            $serviceAlias,
            []
        );

        $options = Options::get(
            $serviceConfig,
            'options',
            []
        );

        return Options::get(
            $options,
            $key,
            $default
        );
    }
}
