<?php
return [
    'instances' => [
        Psr\Http\Message\ServerRequestInterface::class => T\Services\Request::capture(),
    ],
    'interfaces' => [
        T\Interfaces\FSInterface::class => [
            'bind' => T\Services\FS::class,
            'arguments' => [
                BASEDIR . '/'
            ]
        ],
    ],
    'mutable' => [
//        T\Interfaces\LangInterface::class => [
//            'alias' => 'Lang',
//            'bind' => T\Services\Lang::class,
//            'arguments' => [
//                BASEDIR . '/resources/languages'
//            ]
//        ],
        T\Interfaces\ConfigInterface::class  => [
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
        T\Interfaces\DBInterface::class => T\Services\Mongodb::class,
        T\Interfaces\HashInterface::class => T\Services\Hash::class,
        T\Interfaces\ViewInterface::class => T\Services\View::class,
        T\Interfaces\RouteInterface::class => T\Services\Route::class,
        T\Interfaces\KernelInterface::class => T\Services\Kernel::class,
    ],
];
