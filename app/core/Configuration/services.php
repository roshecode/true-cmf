<?php

return [
    'interfaces' => [
        'Twig_LoaderInterface' => 'Twig_Loader_Filesystem',
    ],
    'singletons' => [
        'FS'        => CORE_SERVICES . 'FileSystem\FS',
        'Lang'      => CORE_SERVICES . 'Multilingual\Lang',
        'Config'    => CORE_SERVICES . 'Configuration\Config',
        'View'      => CORE_SERVICES . 'View\Twig',
    ],
    'settings' => [
        'Config' => [
            COREDIR . 'Configuration', // File system basedir
            'main.php' // file to load
        ],
        'View' => [
            COREDIR . 'Themes', // Loader interface basedir
            ['cache' => BASEDIR . '/cache/themes', 'debug' => true, 'auto_reload' => true] // Environment settings
        ]
    ]
];
