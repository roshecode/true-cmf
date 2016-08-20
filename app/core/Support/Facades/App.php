<?php

namespace Truth\Support\Facades;

use Truth\Facades\Facade;
use Truth\IoC\Container;

/**
 * Class App
 * @method static add(string $interface, string|object $service, bool $share = true)
 * @method static has(string $interface)
 * @method static get(string $interface)
 * @package True\IoC\Facades
 */
final class App// extends Facade
{
    /**
     * @var Container
     */
    protected static $instance;

    public static function init() {
//        parent::setInstance(new Container());
        self::$instance = new Container();

//        foreach (Config::get('services') as $service) {
//            $reflection_class = new \ReflectionClass($service);
//            App::add($reflection_class->getInterfaceNames()[0], $service);
//        }
        foreach (Config::get('aliases') as $name => /** class Facade */$class) {
            $class::register();
        }
//        dd(self::$instance);
//        dd($reflection_class->getInterfaceNames());
    }

    public static function getInstance() {
        return self::$instance;
    }

    public static function __callStatic($name, $arguments) {
//        return static::$instance->$name($arguments);
        return call_user_func_array([static::$instance, $name], $arguments);
    }
}
