<?php
namespace T\Abstracts;

use T\Services\Container\Box;

abstract class Facade
{
    /**
     * @var Box $box
     */
    protected static $box;
    
    final public static function __register(Box &$box) {
        static::$box = $box;
    }
    
    protected static function getFacadeAccessor() {
        return null;
    }
    
    final public static function __callStatic($name, $arguments) {
        return call_user_func_array([static::$box->make(static::getFacadeAccessor()), $name], $arguments);
    }
}
