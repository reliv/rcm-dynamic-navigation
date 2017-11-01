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
     */
    public static function invoke(
        array $linksData
    ): array {
        $links = [];

        foreach ($linksData as $key => $linkData) {
            if(!is_array($linkData)) {
                var_dump($key, $linkData);
                continue;
            }
            $links[] = LinkFromData::invoke(
                $linkData
            );
        }

        return $links;
    }
}
