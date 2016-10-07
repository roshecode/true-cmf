<?php

return [
    'interfaces' => [
        Twig_LoaderInterface::class                         => Twig_Loader_Filesystem::class,
    ],
    'mutables' => [
        T\Support\Interfaces\LanguageInterface::class       => T\Support\Services\Multilingual\Lang::class,
    ],
    'singletons' => [
        T\Support\Interfaces\ConfigurationInterface::class  => T\Support\Services\Configuration\Config::class,
        T\Support\Interfaces\RouterInterface::class         => T\Support\Services\Routing\Router::class,
        T\Support\Interfaces\ViewInterface::class           => T\Support\Services\View\Twig::class,
        'FS'                                                => T\Support\Services\FileSystem\FS::class,
    ],

    'aliases' => [
        'Lang'      => T\Support\Interfaces\LanguageInterface::class,
        'Config'    => T\Support\Interfaces\ConfigurationInterface::class,
        'Route'     => T\Support\Interfaces\RouterInterface::class,
        'View'      => T\Support\Interfaces\ViewInterface::class,
    ],

    'settings' => [
        'FS'        => [BASEDIR],
        'Lang'      => [BASEDIR . 'core/Languages', 'en-EN.php'],
        'Config'    => [BASEDIR . 'configuration', ['main.php']],
        'Route'     => ['true'],
        'View'      => [BASEDIR . 'themes', // Loader interface basedir
            ['cache' => BASEDIR . 'cache/themes', 'debug' => true, 'auto_reload' => true] // Environment settings
        ]
    ],
];
