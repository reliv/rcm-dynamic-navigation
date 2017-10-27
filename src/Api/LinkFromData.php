<?php

namespace RcmDynamicNavigation\Api;

use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class LinkFromData
{
    /**
     * @param array $linkData
     *
     * @return NavLink
     */
    public static function invoke(
        array $linkData
    ): NavLink
    {
        $linkData = self::buildBcOptions($linkData);

        $subLinksData = Options::get(
            $linkData,
            'links',
            []
        );

        return new NavLink(
            Options::getRequired(
                $linkData,
                'display'
            ),
            Options::getRequired(
                $linkData,
                'href'
            ),
            Options::get(
                $linkData,
                'target',
                ''
            ),
            LinksFromData::invoke($subLinksData),
            Options::get(
                $linkData,
                'class',
                ''
            ),
            Options::get(
                $linkData,
                'isAllowedService',
                'default'
            ),
            Options::get(
                $linkData,
                'isAllowedServiceOptions',
                []
            ),
            Options::get(
                $linkData,
                'renderService',
                'default'
            ),
            Options::get(
                $linkData,
                'renderServiceOptions',
                []
            )
        );
    }

    protected static function buildBcOptions(array $linkData)
    {
        $linkData = self::buildBcIsAllowedServiceOptions($linkData);
        $linkData = self::buildBcLogOutServiceOptions($linkData);
        return self::buildBcLogInServiceOptions($linkData);
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcIsAllowedServiceOptions(array $linkData)
    {
        if (!empty($linkData['permissions'])) {
            $linkData['isAllowedService'] = 'show-if-has-access-role';
            $linkData['isAllowedServiceOptions'] = [
                'permissions' => $linkData['permissions'],
            ];

            unset($linkData['permissions']);
        }

        return $linkData;
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcLogOutServiceOptions(array $linkData)
    {
        if ($linkData['href'] == '/login?logout=1') {
            $linkData['renderService'] = 'log-out';
            $linkData['isAllowedService'] = 'show-if-logged-in';
        }

        return $linkData;
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcLogInServiceOptions(array $linkData)
    {
        if ($linkData['href'] == '/login') {
            $linkData['renderService'] = 'log-in';
            $linkData['isAllowedService'] = 'show-if-not-logged-in';
        }

        return $linkData;
    }
}
