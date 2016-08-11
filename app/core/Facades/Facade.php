<?php

namespace True\Facades;

abstract class Facade
{
    protected static $instance;

    protected final static function setInstance($object) {
        static::$instance = new $object();
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments) {
        static::$instance->$name($arguments);
    }
}