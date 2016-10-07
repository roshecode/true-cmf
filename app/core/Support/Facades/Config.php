<?php

namespace T\Support\Facades;

use T\Support\Abstracts\Facade;

class Config extends Facade
{
    protected static function getFacadeAccessor() {
//        return (new \ReflectionClass(static::class))->getShortName();
        return 'Config';
    }
}
