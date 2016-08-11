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
