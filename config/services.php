<?php
return [
    'interfaces' => [
        Twig_LoaderInterface::class => Twig_Loader_Filesystem::class,
    ],
    'mutable'    => [
        T\Interfaces\Lang::class => T\Services\Multilingual\Lang::class,
    ],
    'singletons' => [
        T\Interfaces\Foundation\Http\Kernel::class => T\Services\Foundation\Http\Kernel::class,
        T\Interfaces\Config::class  => T\Services\Config\Config::class,
        T\Interfaces\Router::class  => T\Services\Routing\Router::class,
        T\Interfaces\View::class    => T\Services\View\Twig::class,
        'FS'                        => T\Services\FileSystem\FS::class,
    ],
    'aliases'    => [
//        'Request' => T\Interfaces\Request::class,
        'Lang'    => T\Interfaces\Lang::class,
        'Config'  => T\Interfaces\Config::class,
        'Route'   => T\Interfaces\Router::class,
        'View'    => T\Interfaces\View::class,
        'Kernel'  => T\Interfaces\Foundation\Http\Kernel::class
    ],
    'settings'   => [
//        'Request' => [$_SERVER['HTTP_HOST'], $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']],
//        'Request'   => ['true', 'GET', '/'],
        'FS'      => [__DIR__],
        'Lang'    => [BASEDIR . 'resources/languages'],
        'Config'  => [BASEDIR . 'config', ['main.php']],
        'Route'   => ['true'],
        'View'    => [
            BASEDIR . 'resources/themes', // Loader interface basedir
            ['cache' => BASEDIR . 'cache/themes', 'debug' => true, 'auto_reload' => true] // Environment settings
        ],
        'Kernel' => [] // todo: fix - box cannot not register service without it
    ],
];
