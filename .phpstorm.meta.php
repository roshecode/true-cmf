<?php

namespace PHPSTORM_META {

    $STATIC_METHOD_TYPES = [
        \T\Support\Services\Locator\Box::make('') => [
            'Config' instanceof \T\Support\Facades\Config
        ],
        \T\Support\Facades\Config::getCurrentThemeName() => [ '' ],
        new \T\Support\Services\Locator\Box() => [
            'UserRepository' instanceof \T\UserRepository
        ],
    ];
}
