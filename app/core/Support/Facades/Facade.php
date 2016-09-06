<?php

namespace Truth\Support\Facades;

abstract class Facade
{
    /**
     * @var \Truth\IoC\Box $box
     */
    protected static $box;

    public static function init(&$box) {
        self::$box = $box;
    }

    abstract protected function getFacadeAccessor();

    final public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$box->make(static::getFacadeAccessor()), $name], $arguments);
//        return call_user_func_array(self::$box->make(static::getFacadeAccessor())->$name, $arguments);
    }
}