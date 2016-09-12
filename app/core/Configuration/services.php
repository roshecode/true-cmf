<?php

return [
    'interfaces' => [
        'Twig_LoaderInterface' => 'Twig_Loader_Filesystem',
    ],
    'singletons' => [
        'FS'        => CORE_SERVICES . 'FileSystem\FS',
        'Config'    => CORE_SERVICES . 'Configuration\Config',
        'View'      => CORE_SERVICES . 'View\Twig',
    ],
    'mutables' => [
        'Lang'      => CORE_SERVICES . 'Multilingual\Lang',
    ],
    'settings' => [
        'Config' => [
            COREDIR . 'Configuration', // File system basedir
            'main.php' // file to load
        ],
        'View' => [
            COREDIR . 'Themes', // Loader interface basedir
            ['cache' => BASEDIR . 'cache/themes', 'debug' => true, 'auto_reload' => true] // Environment settings
        ]
    ]
];
