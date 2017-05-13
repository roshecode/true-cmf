<?php
return [
    'interfaces' => [
        Twig_LoaderInterface::class => Twig_Loader_Filesystem::class,
        T\Interfaces\FS::class => [
            'bind' => T\Services\Filesystem\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
    ],
    'mutable'    => [
        T\Interfaces\Lang::class => [
            'alias' => 'Lang',
            'bind' => T\Services\Multilingual\Lang::class,
            'arguments' => [
                BASEDIR . '/resources/languages'
            ]
        ],
        T\Interfaces\Config::class  => [
            'alias' => 'Config',
            'bind' => T\Services\Config\Config::class,
            'arguments' => [
                BASEDIR . '/config',
                [
                    'main.php'
                ]
            ]
        ],
    ],
    'singletons' => [
        'Filesystem' => [
            'bind' => T\Services\Filesystem\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
        T\Interfaces\View::class    => [
            'alias' => 'View',
            'bind' => T\Services\View\Twig::class,
            'arguments' => [
                BASEDIR . '/resources/themes',
                [
                    'cache' => BASEDIR . 'cache/themes',
                    'debug' => true,
                    'auto_reload' => true
                ]
            ]
        ],
        T\Interfaces\Route::class  => [
            'alias' => 'Route',
            'bind' => T\Services\Routing\Route::class,
            'arguments' => [
                'true.app'
            ]
        ],
        T\Interfaces\Foundation\Http\Kernel::class => [
            'alias' => 'Kernel',
            'bind' => T\Services\Foundation\Http\Kernel::class
        ],
    ],
];
