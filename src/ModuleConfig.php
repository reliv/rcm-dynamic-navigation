<?php

namespace RcmDynamicNavigation;

use RcmDynamicNavigation\Api\Acl\IsLinkAllowed;
use RcmDynamicNavigation\Api\Acl\IsLinkAllowedDefaultFactory;
use RcmDynamicNavigation\Api\GetIsAllowedServicesConfig;
use RcmDynamicNavigation\Api\GetIsAllowedServicesConfigFactory;
use RcmDynamicNavigation\Api\GetRenderServicesConfig;
use RcmDynamicNavigation\Api\GetRenderServicesConfigFactory;
use RcmDynamicNavigation\Controller\ApiAdminController;
use RcmDynamicNavigation\Controller\ApiAdminControllerFactory;
use RcmDynamicNavigation\Controller\RenderLinksController;
use RcmDynamicNavigation\Controller\RenderLinksControllerFactory;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    protected $defaultInstanceConfig
    = [
        "links" => [
            0 => [
                'id' => '',
                'display' => 'Untitled Link',
                'href' => "#",
                'class' => '',
                'target' => '',
                'links' => [],
                'renderService' => 'default',
                'isAllowedService' => 'default',
                'options' => [],
            ],
        ],
    ];

    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
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

            'dependencies' => [
                'factories' => [
                    \RcmDynamicNavigation\Api\Acl\IsAllowedAny::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedAnyFactory::class,

                    \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfLoggedIn::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfLoggedInFactory::class,

                    \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfNotLoggedIn::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserIfNotLoggedInFactory::class,

                    \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRoles::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRolesFactory::class,

                    \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserHasAccessRoleWithoutRoleInheritanceFactory::class,

                    \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserSiteAdmin::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserSiteAdminFactory::class,

                    \RcmDynamicNavigation\Api\Acl\IsAllowedRoles::class
                    => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRolesFactory::class,

                    IsLinkAllowed::class
                    => IsLinkAllowedDefaultFactory::class,

                    /* CONTROLLERS */

                    ApiAdminController::class
                    => ApiAdminControllerFactory::class,

                    RenderLinksController::class
                    => RenderLinksControllerFactory::class,

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

                    GetIsAllowedServicesConfig::class
                    => GetIsAllowedServicesConfigFactory::class,

                    \RcmDynamicNavigation\Api\GetRenderServiceConfig::class
                    => \RcmDynamicNavigation\Api\GetRenderServiceConfigFactory::class,

                    \RcmDynamicNavigation\Api\GetRenderServiceConfigOption::class
                    => \RcmDynamicNavigation\Api\GetRenderServiceConfigOptionFactory::class,

                    GetRenderServicesConfig::class
                    => GetRenderServicesConfigFactory::class,
                ],
            ],

            'rcmPlugin' => [
                'RcmDynamicNavigation' => [
                    'type' => 'Common',
                    'display' => 'Dynamic Navigation Menu',
                    'tooltip' => 'An editable navigation menu',
                    'icon' => '',
                    'canCache' => false,
                    'editJs' => '/modules/rcm-dynamic-navigation/edit.js',
                    'defaultInstanceConfig' => $this->defaultInstanceConfig,

                    'isAllowedServices' => [
                        'default' => [
                            'displayName' => 'Show link always (default)',
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
                            'displayName' => 'Show link if user has access role (with role inheritance) [DEPRECATED]',
                            'service' => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserRoles::class,
                            'options' => [
                                'permissions' => '',
                            ],
                        ],
                        'show-if-has-access-role-without-role-inheritance' => [
                            'displayName' => 'Show link if user has access role (without role inheritance)',
                            'service' => \RcmDynamicNavigation\Api\Acl\IsAllowedRcmUserHasAccessRoleWithoutRoleInheritance::class,
                            'options' => [
                                'permissions' => '',
                            ],
                        ],
                    ],
                    'renderServices' => [
                        'default' => [
                            'displayName' => 'Bootstrap rendering (default)',
                            'service' => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrap::class,
                            'options' => [],
                        ],
                        //'example' => [
                        //    'displayName' => 'Example',
                        //    'service' => \RcmDynamicNavigation\Api\Render\RenderLinkBootstrap::class,
                        //    'options' => [
                        //        'class' => 'my class',
                        //        'href' => '/example',
                        //    ],
                        //],
                    ],
                ],
            ],

            'routes' => [
                'api.rcm-dynamic-navigation.services' => [
                    'name' => 'api.rcm-dynamic-navigation.services',
                    'path' => '/api/rcm-dynamic-navigation/services',
                    'middleware' => ApiAdminController::class,
                    'allowed_methods' => ['GET'],
                ],
                'rcm-dynamic-navigation.render-links.id' => [
                    'name' => 'rcm-dynamic-navigation.render-links.id',
                    'path' => '/rcm-dynamic-navigation/render-links/{id}',
                    'middleware' => [
                        BodyParamsMiddleware::class,
                        RenderLinksController::class,
                    ],
                    'allowed_methods' => ['POST'],
                ],
            ]
        ];
    }
}
