<?php

namespace True\Standards\Container;

use Closure;

/**
 * Representation of a container
 */
interface ContainerInterface
{
    /**
     * Register an existing instance as shared in the container.
     *
     * @param string $abstract
     * @param mixed  $instance
     */
    public function instance(string $abstract, $instance);

    /**
     * Register a binding with the container.
     *
     * @param string|array               $abstract // TODO: bind from array
     * @param string|Closure|Object|null $concrete
     * @param bool                       $shared
     * @param bool                       $mutable
     *
     * @throws \Exception
     */
    public function bind($abstract, $concrete, $shared, $mutable);

    /**
     * Register a shared binding in the container.
     *
     * @param string|array $abstract
     * @param mixed        $concrete
     */
    public function singleton($abstract, $concrete);

    /**
     * Register a mutable singleton in the container
     *
     * @param string|array $abstract
     * @param mixed        $concrete
     */
    public function mutable($abstract, $concrete);

    /**
     * Register an alias for existing interface
     *
     * @param string $alias
     * @param string $abstract
     */
    public function alias(string $alias, $abstract);

    /**
     * Wrap function under makeInstance
     *
     * @param string $abstract
     * @param array  $params
     *
     * @return mixed
     */
    public function make(string $abstract, array $params = []);

    /**
     * Create new instance of concrete class
     *
     * @param string     &$concrete
     * @param array      &$params
     * @param array|null &$stack
     * @return object
     * @throws \Exception
     */
    public function create(string &$concrete, array &$params, &$stack = null);

    /**
     * Invoke callable function
     *
     * @param callable   $callable
     * @param array      &$params
     * @param array|null &$stack
     * @return object
     * @throws \Exception
     */
    public function invoke(callable $callable, array &$params, &$stack = null);

    /**
     * Determine if a given type is shared.
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function isShared(string $abstract) : bool;
}
