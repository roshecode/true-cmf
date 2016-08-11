<?php

namespace True\Facades;

abstract class Facade
{
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
        return static::$instance->$name($arguments);
//        return call_user_func_array($name, $arguments);
    }
}