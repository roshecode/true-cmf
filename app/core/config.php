<?php

return [
    'directories' => [
        'languages' => '/core/Languages',
        'themes' => '/core/Themes',
        'cache' => [
            'themes' => '/app/cache/templates'
        ],
    ],
    'localization' => [
        'language' => 'en-EN',
//        'language' => 'ru-RU',
        'timezone' => 3
    ],
    'services' => [
        'True\\Services\\View\\Twig',
        'True\\Services\\Logging\\Monolog'
    ],
    'aliases' => [
//        'Config' => 'True\\Support\\Facades\\Config',
//        'Lang' => 'True\\Support\\Facades\\Lang',
        'View' => True\Support\Facades\View::$class,
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
