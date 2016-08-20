<?php

namespace PHPSTORM_META {

    $STATIC_METHOD_TYPES = [
        \Truth\IoC\LaravelContainer::make('') => [
            'UserRepository' instanceof \Truth\UserRepository
        ],
        new \Truth\IoC\LaravelContainer() => [
            'UserRepository' instanceof \Truth\UserRepository
        ],
    ];
}
