<?php

namespace PHPSTORM_META {

    $STATIC_METHOD_TYPES = [
        \Truth\Support\Services\Locator\Box::make('') => [
            'Config' instanceof \Truth\Support\Facades\Config
        ],
        \Truth\Support\Facades\Config::getCurrentThemeName() => [ '' ],
        new \Truth\Support\Services\Locator\Box() => [
            'UserRepository' instanceof \Truth\UserRepository
        ],
    ];
}
