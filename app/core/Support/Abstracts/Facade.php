<?php

namespace Truth\Support\Abstracts;

abstract class Facade extends Entity
{
    protected static function getFacadeAccessor() {
        return 'undefined';
    }

    final public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$box->make(static::getFacadeAccessor()), $name], $arguments);
    }
}