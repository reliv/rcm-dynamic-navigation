<?php

namespace RcmDynamicNavigation\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Options
{
    /**
     * @param array  $params
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public static function get(
        array $params,
        string $key,
        $default = null
    ) {
        if (array_key_exists($key, $params)) {
            return $params[$key];
        }

        return $default;
    }

    /**
     * @param array  $params
     * @param string $key
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getRequired(
        array $params,
        string $key
    ) {
        if (!array_key_exists($key, $params)) {
            throw new \Exception(
                'Option is missing: ' . $key
                . ' in: ' . var_export($params, true)
            );
        }

        return $params[$key];
    }
}
