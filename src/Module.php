<?php

namespace RcmDynamicNavigation;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Module
{
    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        $moduleConfig = new ModuleConfig();

        $config = $moduleConfig->__invoke();

        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);

        $config['controllers'] = [
            'factories' => [
                'RcmDynamicNavigation'
                => \RcmDynamicNavigation\Controller\PluginControllerFactory::class,
            ]
        ];

        $config['view_helpers'] = [
            'factories' => [
                'rcmDynamicLinksRenderLinks'
                => \RcmDynamicNavigation\View\RenderLinksFactory::class,
            ],
        ];

        $config['view_manager'] = [
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ];

        return $config;
    }
}
