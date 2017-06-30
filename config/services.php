<?php
return [
    'instances' => [
        T\Interfaces\RequestInterface::class => [
            'bind' => T\Services\Request::capture()
        ],
    ],
    'interfaces' => [
        T\Interfaces\FSInterface::class => [
            'bind' => T\Services\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
    ],
    'mutable'    => [
//        T\Interfaces\LangInterface::class => [
//            'alias' => 'Lang',
//            'bind' => T\Services\Lang::class,
//            'arguments' => [
//                BASEDIR . '/resources/languages'
//            ]
//        ],
        T\Interfaces\ConfigInterface::class  => [
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
        T\Interfaces\FSInterface::class => [
            'bind' => T\Services\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
        T\Interfaces\DBInterface::class => [
            'alias' => 'DB',
            'bind' => T\Services\Mongodb::class,
            'arguments' => [
                'mongodb://localhost:27017',
                'rosem'
            ]
        ],
        T\Interfaces\HashInterface::class => [
            'alias' => 'Hash',
            'bind' => T\Services\Hash::class,
            'arguments' => [
                [
                    'cost' => 10
                ]
            ]
        ],
//        \League\Plates\Engine::class => [
//            'arguments' => [BASEDIR . '/resources/views/static']
//        ],
//        \League\Plates\Extension\Asset::class => [
//            'arguments' => [BASEDIR . '/public']
//        ],
        T\Interfaces\ViewInterface::class    => [
            'alias' => 'View',
            'bind' => T\Services\View::class,
        ],
        T\Interfaces\RouteInterface::class  => [
            'alias' => 'Route',
            'bind' => T\Services\Route::class,
            'arguments' => [
                'true.app'
            ]
        ],
        T\Interfaces\KernelInterface::class => [
            'alias' => 'Kernel',
            'bind' => T\Services\Kernel::class
        ],
    ],
];
