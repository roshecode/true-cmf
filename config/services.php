<?php

use True\Standards\Container\ScopeEnum as Scope;
use True\Standards\Container\KeyEnum as Key;

return [
    Scope::Instantiated  => [
        Psr\Http\Message\ServerRequestInterface::class => Core\Services\Request::capture(),
    ],
    Scope::Disposable => [
        Core\Services\Contracts\FS::class     => [
            Key::Concrete  => Core\Services\FS::class,
            Key::Arguments => [
                BASEDIR . '/',
            ],
        ],
        \League\Plates\Engine::class          => [
            Key::Arguments => [
                BASEDIR . '/resources/templates',
            ],
        ],
        \League\Plates\Extension\Asset::class => [
            Key::Arguments => [
                BASEDIR . '/public',
            ],
        ],
    ],
    Scope::Mutable    => [
//        T\Interfaces\LangInterface::class => [
//            'alias' => 'Lang',
//            'bind' => T\Services\Lang::class,
//            'arguments' => [
//                BASEDIR . '/resources/languages'
//            ]
//        ],
        Core\Services\Contracts\Config::class => [
            Key::Concrete  => Core\Services\Config::class,
            Key::Arguments => [
                BASEDIR . '/config',
                [
                    'main.php',
                ],
            ],
        ],
    ],
    Scope::Single => [
        Core\Services\Contracts\DB::class     => [
            Key::Concrete  => Core\Services\DB::class,
            Key::Arguments => [
                getEnv('DB_DRIVER'),
                getEnv('DB_HOST'),
                getEnv('DB_NAME'),
                getEnv('DB_USERNAME'),
                getEnv('DB_PASSWORD'),
                getEnv('DB_CHARSET'),
            ],
        ],
        Core\Services\Contracts\Hash::class   => Core\Services\Hash::class,
        Core\Services\Contracts\View::class   => Core\Services\View::class,
        Core\Services\Contracts\Route::class  => Core\Services\Route::class,
        Core\Services\Contracts\Kernel::class => Core\Services\Kernel::class,
    ],
];
