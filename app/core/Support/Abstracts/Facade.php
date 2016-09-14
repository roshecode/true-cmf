<?php

namespace Truth\Support\Abstracts;

use Truth\Support\Services\Locator\Box;

abstract class Facade
{
    /**
     * @var Box $box
     */
    protected static $box;

    final public static function __register(Box &$box) {
        self::$box = $box;
    }

    protected static function getFacadeAccessor() {
        return null;
    }

    final public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$box->make(static::getFacadeAccessor()), $name], $arguments);
    }
}