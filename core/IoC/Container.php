<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 17.07.2016
 * Time: 9:38
 */

namespace Truef\IoC;


class Container implements \True\Contracts\IoC\Container
{
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Register a binding with the container.
     *
     * @param  string|array $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bind() method.
    }
}