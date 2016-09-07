<?php

namespace Truth\IoC;

use Closure;
use ReflectionClass;
use BadMethodCallException;
use Truth\Support\Abstracts\Entity;
use Truth\Support\Facades\Lang;

class StrictBox {
    /**
     * Self reference
     *
     * @var StrictBox
     */
    protected static $instance;
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];
    protected $resolved = [];

    public function __construct()
    {
        self::$instance = $this;
        Entity::init($this);
    }

    public function getInstance() {
        return self::$instance;
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

/**
 * Class Box
 *
 * @package True\IoC
 */
class Box extends StrictBox
{
    /**
     * Normalize the given class name by removing leading slashes.
     *
     * @param mixed &$service
     */
    protected function fix(&$service) {
//        $service = is_string($service) ? ltrim($service, '\\') : $service;
        $service = ltrim($service, '\\');
    }

    public function __call($name, $arguments) {
        $this->fix($arguments[0]);
        if (is_callable([$parentClass = get_parent_class(), $name])) {
            call_user_func_array($parentClass . '::' . $name, $arguments);
        } else {
            throw new BadMethodCallException(Lang::get('exceptions.bad_method_call'));
        }
    }

    /**
     * Wrap a Closure such that it is shared.
     *
     * @param  Closure  $closure
     * @return Closure
     */
    public function share(Closure $closure)
    {
        return function ($container) use ($closure) {
            // We'll simply declare a static variable within the Closures and if it has
            // not been set we will execute the given Closures to resolve this value
            // and return it back to these consumers of the method as an instance.
            static $object;
            return is_null($object) ? $object = $closure($container) : $object;
        };
    }
//    public function singleton() {}
//    public function share() {}
//    public function tag() {}
//    protected function extractAlias() {}
//    public function wrap() {}
//    public function call() {}
//    protected function isCallableWithAtSign() {}
//    protected function getMethodDependencies() {}
//    protected function getCallReflector() {}
//    protected function addDependencyForCallParameter() {}
}
