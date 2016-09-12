<?php

return [
    'directories' => [
        'languages' => 'core/Languages',
        'configuration' => 'core/Configuration',
        'themes' => 'core/Themes',
        'cache' => [
            'themes' => 'cache/templates'
        ],
    ],
    'localization' => [
        'base_language' => 'en-EN',
        'language' => 'en-EN',
//        'language' => 'ru-RU',
        'timezone' => 3
    ],
    'services' => [
        'True\\Support\\Services\\View\\Twig',
        'True\\Support\\Services\\Logging\\Monolog'
    ],
    'aliases' => [
//        'Config' => 'True\\Support\\Facades\\Config',
//        'Lang' => 'True\\Support\\Facades\\Lang',
        'View' => 'Truth\Support\Facades\View',
//        'Log' => 'True\\Support\\Facades\\Log',
    ],
    'errors' => [
        'display' => true,
        'reporting' => E_ALL
    ],
    'log' => [
        'debug' => '/app/log/debug.log',
        'error'
    ],
    'site' => [
        'theme' => 'default'
    ]
];
