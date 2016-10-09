<?php

namespace T\Facades;

use T\Abstracts\Facade;

class Config extends Facade
{
    protected static function getFacadeAccessor() {
//        return (new \ReflectionClass(static::class))->getShortName();
        return 'Config';
    }
}
