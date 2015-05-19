<?php
return [
    'modules' => [
        // RcmUser dependencies
        'RcmUser',
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/../../../../config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './vendor',
            './vendor/zend',
            './vendor/reliv/',
            './vendor/phpunit/',
        ],
    ],
];