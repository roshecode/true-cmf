<?php

namespace True\Facades;

use True\Support\Facades\App;

abstract class Facade
{
    protected static $name;
    protected static $instance;

    protected final static function setInstance($object) {
        static::$instance = $object;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments) {
//        return static::$instance->$name($arguments);
//        return call_user_func_array([static::$instance, $name], $arguments);
        return call_user_func_array([App::get(static::$name), $name], $arguments);
    }

    public static function test() {
        echo get_called_class();
    }
}