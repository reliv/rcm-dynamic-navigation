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
     * @throws \Exception
     */
    public static function invoke(
        array $linkData
    ): NavLink {
        $linkData = self::buildBcData($linkData);

        $subLinksData = Options::get(
            $linkData,
            'links',
            []
        );

        return new NavLink(
            Options::getRequired(
                $linkData,
                'id'
            ),
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
                'renderService',
                'default'
            ),
            Options::get(
                $linkData,
                'options',
                []
            )
        );
    }

    /**
     * @BC Fill missing values
     *
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcData(array $linkData)
    {
        if (empty($linkData['id'])) {
            // @BC for missing Ids
            $linkData['id'] = 'BC:' . GetGuidV4::invoke();
        }

        if (empty($linkData['target'])) {
            $linkData['target'] = '';
        }

        if (empty($linkData['class'])) {
            $linkData['class'] = '';
        }

        if (empty($linkData['links'])) {
            $linkData['links'] = [];
        }

        if (empty($linkData['isAllowedService'])) {
            $linkData['isAllowedService'] = 'default';
        }

        if (empty($linkData['renderService'])) {
            $linkData['renderService'] = 'default';
        }

        if (empty($linkData['options'])) {
            $linkData['options'] = [];
        }

        return $linkData;
    }
}
