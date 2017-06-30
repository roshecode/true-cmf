<?php
return [
    'instances' => [
        T\Interfaces\Request::class => [
            'bind' => T\Services\Request::capture()
        ]
    ],
    'interfaces' => [
        Twig_LoaderInterface::class => Twig_Loader_Filesystem::class,
        T\Interfaces\FS::class => [
            'bind' => T\Services\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
    ],
    'mutable'    => [
        T\Interfaces\Lang::class => [
            'alias' => 'Lang',
            'bind' => T\Services\Lang::class,
            'arguments' => [
                BASEDIR . '/resources/languages'
            ]
        ],
        T\Interfaces\Config::class  => [
            'alias' => 'Config',
            'bind' => T\Services\Config::class,
            'arguments' => [
                BASEDIR . '/config',
                [
                    'main.php'
                ]
            ]
        ],
    ],
    'singletons' => [
//        T\Interfaces\Box::class => [
//            'bind' => T\Services\Box::class
//        ],
//        T\Interfaces\Request::class => [
//            'bind' => T\Services\Request::class
//        ],
        T\Interfaces\FS::class => [
            'bind' => T\Services\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
        T\Interfaces\DB::class => [
            'alias' => 'DB',
            'bind' => T\Services\Mongodb::class,
            'arguments' => [
                'mongodb://localhost:27017',
                'rosem'
            ]
        ],
        T\Interfaces\Hash::class => [
            'alias' => 'Hash',
            'bind' => T\Services\Hash::class,
            'arguments' => [
                [
                    'cost' => 10
                ]
            ]
        ],
        T\Interfaces\View::class    => [
            'alias' => 'View',
            'bind' => T\Services\Twig::class,
            'arguments' => [
                BASEDIR . '/resources/themes', // rootPath
                null, // paths
                [
                    'cache' => BASEDIR . 'cache/themes',
                    'debug' => true,
                    'auto_reload' => true
                ]
            ]
        ],
        T\Interfaces\Route::class  => [
            'alias' => 'Route',
            'bind' => T\Services\Route::class,
            'arguments' => [
                'true.app'
            ]
        ],
        T\Interfaces\Kernel::class => [
            'alias' => 'Kernel',
            'bind' => T\Services\Kernel::class
        ],
    ],
];