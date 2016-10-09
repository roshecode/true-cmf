<?php

return [
    'interfaces' => [
        Twig_LoaderInterface::class                         => Twig_Loader_Filesystem::class,
    ],
    'mutables' => [
        T\Interfaces\LangInterface::class       => T\Services\Multilingual\Lang::class,
    ],
    'singletons' => [
        T\Interfaces\RequestInterface::class        => T\Services\Http\Request::class,
        T\Interfaces\ConfigInterface::class         => T\Services\Config\Config::class,
        T\Interfaces\RouterInterface::class         => T\Services\Routing\Router::class,
        T\Interfaces\ViewInterface::class           => T\Services\View\Twig::class,
        'FS'                                        => T\Services\FileSystem\FS::class,
    ],

    'aliases' => [
        'Request'   => T\Interfaces\RequestInterface::class,
        'Lang'      => T\Interfaces\LangInterface::class,
        'Config'    => T\Interfaces\ConfigInterface::class,
        'Route'     => T\Interfaces\RouterInterface::class,
        'View'      => T\Interfaces\ViewInterface::class,
    ],

    'settings' => [
        'Request'   => [$_SERVER['HTTP_HOST'], $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']],
//        'Request'   => ['true', 'GET', '/'],
        'FS'        => [BASEDIR],
        'Lang'      => [BASEDIR . 'languages/en-EN', ['debug.php', 'status.php']],
        'Config'    => [BASEDIR . 'config', ['main.php']],
        'Route'     => ['true'],
        'View'      => [BASEDIR . 'themes', // Loader interface basedir
            ['cache' => BASEDIR . 'cache/themes', 'debug' => true, 'auto_reload' => true] // Environment settings
        ]
    ],
];
