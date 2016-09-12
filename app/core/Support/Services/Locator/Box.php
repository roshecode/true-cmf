<?php

namespace Truth\Support\Services\Locator;

use Closure;
use ReflectionClass;
use Truth\Support\Abstracts\ServiceProvider;

class Box extends ServiceProvider
{
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];
    protected $resolved = [];

    public function __construct()
    {
        ServiceProvider::register($this);
    }

    public function getInstance() {
        return self::$box;
    }

    protected function getStack(&$class, array &$stack = []) {
        $constructor = (new ReflectionClass($class))->getConstructor();
        if($params = $constructor ? $constructor->getParameters() : false) {
            foreach ($params as $index => $param) {
                $nextClass = $param->getClass();
                if ($nextClass) {
                    $nextClassName = $nextClass->name;
                    $stack[$nextClassName] = $this->getStack($nextClassName);
                } else {
                    $stack[] = null;
                }
            }
        };
        return $stack;
    }

    protected function build($stack, &$params) {
        foreach ($stack as $class => $nextStack) {
            $stack[$class] = is_numeric($class) ? array_pop($params) : $this->makeInstance($class, $params);
        }
        return $stack;
    }

    protected function newInstance(&$concrete, &$params) {
        return $params ?
            (new ReflectionClass($concrete))->newInstanceArgs($this->build($this->getStack($concrete), $params)) :
            new $concrete;
    }

    protected function getMakeClosure(&$abstract, &$concrete, &$shared, $callback) {
        return $shared ?
            function(&$params) use($abstract, $concrete, $callback) {
                return ($shared = &$this->bindings[$abstract]['shared']) === true ?
                    $shared = $callback($concrete, $params) : $shared;
            } :
            function(&$params) use($concrete, $callback) {
                return $callback($concrete, $params);
            };
    }

    protected function setStringBinding(&$abstract, &$concrete, &$shared) {
        $this->bindings[$abstract] = [
            'make' => $this->getMakeClosure($abstract, $concrete, $shared, function (&$concrete, &$params) {
                return $this->newInstance($concrete, $params);
            }),
            'shared' => $shared
        ];
    }

    protected function setClosureBinding($abstract, $closure, $shared) {
        $this->bindings[$abstract] = [
            'make' => $this->getMakeClosure($abstract, $closure, $shared, 'call_user_func_array'),
            'shared' => $shared
        ];
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  mixed   $instance
     * @return void
     */
    public function instance($abstract, $instance) {
        $this->bindings[$abstract] = [
            'make' => function() use($instance) { return $instance; },
            'shared' => $instance
        ];
    }

    /**
     * Register a binding with the container.
     *
     * @param  string|array  $abstract
     * @param  \Closure|string|null  $concrete
     * @param  bool  $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_string($concrete)) {
            $this->setStringBinding($abstract, $concrete, $shared); // return new or instance concrete
        } elseif ($concrete instanceof Closure) {
            $this->setClosureBinding($abstract, $concrete, $shared); // return new or instance closure
        } elseif (is_null($concrete)) {
            $this->setStringBinding($abstract, $abstract, $shared); // return new or instance abstract
        } elseif (is_object($concrete)) {
            $this->instance($abstract, $concrete);
        }
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string|array $abstract
     * @param  mixed $concrete
     * @return void
     */
    public function singleton($abstract, $concrete) {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    protected function makeInstance($abstract, array &$parameters = []) {
//        return isset($this->bindings[$abstract]) ?
//            $this->bindings[$abstract]['make']($parameters) : $this->newInstance($abstract, $parameters);
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]['make']($parameters);
        } else {
            isset($this->resolved[$abstract]) ? ++$this->resolved[$abstract] : $this->resolved[$abstract] = 1;
            return $this->newInstance($abstract, $parameters);
        }
    }

    /**
     * Wrap function under makeInstance
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = []) {
        $abstractVar = $abstract;
        $reverseParameters = array_reverse($parameters);
        return $this->makeInstance($abstractVar, $reverseParameters);
    }

    /**
     * Determine if a given type is shared.
     *
     * @param  string  $abstract
     * @return bool
     */
    public function isShared($abstract)
    {
        return !!$this->bindings[$abstract]['shared'];
    }
}