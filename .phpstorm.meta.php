<?php
namespace PHPSTORM_META { // we want to avoid the pollution
    $STATIC_METHOD_TYPES = [
        new \T\Interfaces\Box => [
            '' == '@',
            \T\Interfaces\Route::class instanceof \T\Interfaces\Route,
        ],
        \T\Interfaces\Box::make('') => [
            '' == '@',
            \T\Interfaces\Route::class instanceof \T\Interfaces\Route,
        ],
        \T\Facades\Box::make('') => [
            '' == '@',
            \T\Interfaces\Route::class instanceof \T\Interfaces\Route,
        ]
    ];
}