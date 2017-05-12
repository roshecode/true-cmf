<?php
namespace T\Interfaces;

use Closure;

interface Box
{
    /**
     * Register an existing instance as shared in the container.
     *
     * @param string $abstract
     * @param mixed  $instance
     */
    public function instance($abstract, $instance);
    
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
    public function alias($alias, $abstract);
    
    /**
     * Wrap function under makeInstance
     *
     * @param string $abstract
     * @param array  $params
     *
     * @return mixed
     */
    public function make($abstract, array $params);
    
    /**
     * Register a binding first and then make an instance
     *
     * @param string|array $abstract
     * @param array        $params
     *
     * @return mixed
     */
    public function create($abstract, array $params);
    
    /**
     * Determine if a given type is shared.
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function isShared($abstract);
}
