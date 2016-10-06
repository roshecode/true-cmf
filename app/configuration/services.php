<?php

return [
    'interfaces' => [
        'Twig_LoaderInterface' => 'Twig_Loader_Filesystem',
//        'Truth\Support\Services\FileSystem\FS'          => CORE_SERVICES . 'FileSystem\FS', // TODO: FS interface
    ],
    'singletons' => [
        'FS'        => 'Truth\Support\Services\FileSystem\FS',
        'Truth\Support\Services\Configuration\Config'   => CORE_SERVICES . 'Configuration\Config',
        'Truth\Support\Services\Routing\Router'         => CORE_SERVICES . 'Routing\Router',
        'Truth\Support\Interfaces\ViewInterface'        => CORE_SERVICES . 'View\Twig',
    ],
    'mutables' => [
        'Truth\Support\Services\Multilingual\Lang'      => CORE_SERVICES . 'Multilingual\Lang', // TODO: Lang interface
    ],
    'aliases' => [
        'Config'    => 'Truth\Support\Services\Configuration\Config',
        'Lang'      => 'Truth\Support\Services\Multilingual\Lang',
        'Route'     => 'Truth\Support\Services\Routing\Router',
        'View'      => 'Truth\Support\Interfaces\ViewInterface',
    ],
    'settings' => [
        'FS'        => [BASEDIR],
        'Lang'      => [BASEDIR . 'core/Languages', 'en-EN.php'],
        'Config'    => [BASEDIR . 'configuration', ['main.php']],
        'View'      => [BASEDIR . 'themes', // Loader interface basedir
            ['cache' => BASEDIR . 'cache/themes', 'debug' => true, 'auto_reload' => true] // Environment settings
        ]
    ],
];
