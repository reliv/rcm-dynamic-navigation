<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.

 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
return [

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-dynamic-navigation/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm/modules.css' => [
                    'modules/rcm-dynamic-navigation/rcm-dynamic-navigation.css'
                ],
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-dynamic-navigation/edit.js',
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            'RcmDynamicNavigation' => \RcmDynamicNavigation\Controller\PluginControllerFactory::class,
        ]
    ],

    'rcmPlugin' => [
        'RcmDynamicNavigation' => [
            'type' => 'Common',
            'display' => 'Dynamic Navigation Menu',
            'tooltip' => 'An editable navigation menu',
            'icon' => '',
            'canCache' => false,
            'editJs' => '/modules/rcm-dynamic-navigation/edit.js',
            'defaultInstanceConfig' => include __DIR__ . '/defaultInstanceConfig.php',

            'isAllowedServices' => [
                'default' => [
                    'displayName' => 'Show link always',
                    'service' => \RcmDynamicNavigation\Api\Acl\IsAllowedAny::class,
                    'options' => [],
                ],
                'show-if-logged-in' => [
                    'displayName' => 'Show link if logged in',
                    'service' => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfLoggedIn::class,
                    'options' => [],
                ],
                'show-if-not-logged-in' => [
                    'displayName' => 'Show link if NOT logged in',
                    'service' => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfNotLoggedIn::class,
                    'options' => [],
                ],
                'show-if-has-access-role' => [
                    'displayName' => 'Show link if user has access role',
                    'service' => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRoles::class,
                    'options' => [
                        'permissions' => '',
                    ],
                ],
            ],
            'renderServices' => [
                'default' => [
                    'displayName' => 'Bootstrap rendering',
                    'service' => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrap::class,
                    'options' => [],
                ],
                'log-in' => [
                    'displayName' => 'Log in Bootstrap rendering',
                    'service' => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrap::class,
                    'options' => [
                        'systemClass' => 'rcmDynamicNavigationLogin rcmDynamicNavigationAuthMenuItem',
                        'href' => '/login',
                    ],
                ],
                'log-out' => [
                    'displayName' => 'Log out Bootstrap rendering',
                    'service' => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrap::class,
                    'options' => [
                        'systemClass' => 'rcmDynamicNavigationLogout rcmDynamicNavigationAuthMenuItem',
                        'href' => '/login?logout=1',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            \RcmDynamicNavigation\Api\Acl\IsAllowedAny::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedAnyFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedAdmin::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserSiteAdminFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedIfLoggedIn::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfLoggedInFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfLoggedIn::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfLoggedInFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfNotLoggedIn::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfNotLoggedInFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRoles::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRolesFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserSiteAdmin::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserSiteAdminFactory::class,

            \RcmDynamicNavigation\Api\Acl\IsAllowedRoles::class
            => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRolesFactory::class,

            /* RENDER */
            \RcmDynamicNavigation\Api\Render\RenderLink::class
            => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrapFactory::class,

            \RcmDynamicNavigation\Api\Render\RenderLinkBootstrap::class
            => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrapFactory::class,

            \RcmDynamicNavigation\Api\Render\RenderLinkOption::class
            => \RcmDynamicNavigation\Api\Render\RenderLinkOptionFactory::class,

            \RcmDynamicNavigation\Api\Render\RenderLinks::class
            => \RcmDynamicNavigation\Api\Render\RenderLinksBootstrapFactory::class,

            \RcmDynamicNavigation\Api\Render\RenderLinksBootstrap::class
            => \RcmDynamicNavigation\Api\Render\RenderLinksBootstrapFactory::class,

            /* GENERAL API */
            \RcmDynamicNavigation\Api\GetIsAllowedServiceConfig::class
            => \RcmDynamicNavigation\Api\GetIsAllowedServiceConfigFactory::class,

            \RcmDynamicNavigation\Api\GetRenderServiceConfig::class
            => \RcmDynamicNavigation\Api\GetRenderServiceConfigFactory::class,

            \RcmDynamicNavigation\Api\GetRenderServiceConfigOption::class
            => \RcmDynamicNavigation\Api\GetRenderServiceConfigOptionFactory::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'rcmDynamicLinksRenderLinks' => \RcmDynamicNavigation\View\RenderLinksFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
