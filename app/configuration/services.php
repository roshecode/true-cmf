<?php

return [
    'interfaces' => [
        'Twig_LoaderInterface'                          => Twig_Loader_Filesystem::class,
    ], 'mutables' => [
        'Truth\Support\Services\Multilingual\Lang'      => Truth\Support\Services\Multilingual\Lang::class,
    ], 'singletons' => [
        'Truth\Support\Services\Configuration\Config'   => Truth\Support\Services\Configuration\Config::class,
        'Truth\Support\Services\Routing\Router'         => Truth\Support\Services\Routing\Router::class,
        'Truth\Support\Interfaces\ViewInterface'        => Truth\Support\Services\View\Twig::class,
        'FS'                                            => Truth\Support\Services\FileSystem\FS::class,
    ],

    'aliases' => [
        'Config'    => Truth\Support\Services\Configuration\Config::class,
        'Lang'      => Truth\Support\Services\Multilingual\Lang::class,
        'Route'     => Truth\Support\Services\Routing\Router::class,
        'View'      => Truth\Support\Interfaces\ViewInterface::class,
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
