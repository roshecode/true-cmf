<?php

return [
    'directories' => [
        'languages' => 'core/Languages',
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
    'errors' => [
        'display' => true,
        'reporting' => E_ALL
    ],
    'log' => [
        'debug' => '/app/log/debug.log',
        'error'
    ],
    'site' => [
        'theme' => 'basic'
    ]
];
