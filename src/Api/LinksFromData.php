<?php

namespace RcmDynamicNavigation\Api;

use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class LinksFromData
{
    /**
     * @param array $linksData
     *
     * @return NavLink[]
     * @throws \Exception
     */
    public static function invoke(
        array $linksData
    ): array {
        $links = [];

        foreach ($linksData as $key => $linkData) {
            if (!is_array($linkData)) {
                throw new \Exception(
                    'Invalid Link Data :' . json_encode($linkData, 0, 10)
                );
            }
            $links[] = LinkFromData::invoke(
                $linkData
            );
        }

        return $links;
    }
}
