<?php
namespace T\Abstracts;

use T\Services\Box;
use RuntimeException;

abstract class Facade
{
    /**
     * The container
     *
     * @var Box $box
     */
    protected static $box;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $resolvedInstances;

    /**
     * Set the box instance.
     *
     * @param  Box &$box
     * @return void
     */
    final public static function __register(Box &$box) {
        static::$box = $box;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string|object
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor() {
        throw new RuntimeException('getFacadeAccessor method must be overwritten');
    }

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param  string|object  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        return is_object($name)
            ? $name
            : isset(static::$resolvedInstances[$name])
                ? static::$resolvedInstances[$name]
                : static::$resolvedInstances[$name] = static::$box[$name];
    }

    /**
     * Clear a resolved facade instance.
     *
     * @param  string  $name
     * @return void
     */
    public static function clearResolvedFacadeInstance($name)
    {
        unset(static::$resolvedInstances[$name]);
    }

    /**
     * Clear all of the resolved facade instances.
     *
     * @return void
     */
    public static function clearResolvedFacadeInstances()
    {
        static::$resolvedInstances = [];
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    final public static function __callStatic($method, array $args) {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
