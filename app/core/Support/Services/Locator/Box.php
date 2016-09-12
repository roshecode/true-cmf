<?php

namespace Truth\Support\Services\Locator;

use Closure;
use ReflectionClass;
use SplFixedArray;
use Truth\Support\Abstracts\ServiceProvider;

class Box
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
        return $this;
    }

    /**
     * Get stack of classes and parameters for automatic building
     *
     * @param string $class
     * @param array $stack
     * @return array
     */
    protected function getStack(&$class, array &$stack = []) {
        $constructor = (new ReflectionClass($class))->getConstructor();
        if($params = $constructor ? $constructor->getParameters() : false) {
            $index = count($params);
            while ($index) {
                $stack[] = $params[--$index]->getClass();
            }
        };
        return $stack;
    }

    /**
     * Build and inject all dependencies with parameters
     *
     * @param array $stack
     * @param array $params
     * @return SplFixedArray
     */
    protected function build(array &$stack, array &$params) {
        $index = 0;
        $length = count($stack);
        $built = new SplFixedArray($length);
        while ($index < $length) {
            $item = &$stack[$index]->name;
            $built[$length - ++$index] = $item ? $this->makeInstance($item, $params) : array_pop($params);
        }
        return $built;
    }

    /**
     * Create new instance of concrete class
     *
     * @param string $concrete
     * @param array $stack
     * @param array $params
     * @return object
     *
     * @throws \Exception
     */
    protected function newInstance(&$concrete, array $stack, array &$params) {
        if (class_exists($concrete)) {
            return $params ?
                (new ReflectionClass($concrete))->
                    newInstanceArgs($this->build($stack, $params)->toArray()) :
                new $concrete;
        } else {
            throw new \Exception('Class ' . $concrete . ' does not exist!!!'); // TODO: more pretty
        }
    }

    /**
     * Get closure for building bind class
     *
     * @param string $abstract
     * @param string $concrete
     * @param bool $shared
     * @param bool $mutable
     * @param Closure|string $callback
     * @return Closure
     */
    protected function getMakeClosure(&$abstract, &$concrete, &$shared, &$mutable, $callback) {
        return $shared ?
            $mutable ?
                function(&$params) use(&$abstract, &$concrete, &$callback) {
                    $binding = &$this->bindings[$abstract];
                    $shared = &$binding['shared'];
                    $stack = &$binding['stack'];
                    return $shared = ($shared === true ? $callback($concrete, $stack = $this->getStack($concrete), $params) :
                        ($params ? $callback($concrete, $stack, $params) : $shared));
                } :
                function(&$params) use(&$abstract, &$concrete, &$callback) {
                    return ($shared = &$this->bindings[$abstract]['shared']) === true ?
                        $shared = $callback($concrete, $this->getStack($concrete), $params) : $shared;
                } :
            function(&$params) use(&$abstract, &$concrete, &$callback) {
                $stack = &$this->bindings[$abstract]['stack'];
                return $callback($concrete, $stack = $stack ? $stack : $this->getStack($concrete), $params);
            };
    }

    /**
     * Set closure for building from string
     *
     * @param string $abstract
     * @param string $concrete
     * @param bool $shared
     * @param bool $mutable
     */
    protected function setStringBinding(&$abstract, &$concrete, &$shared, &$mutable) {
        $this->bindings[$abstract] = [
            'make' => $this->getMakeClosure($abstract, $concrete, $shared, $mutable, function (&$concrete, $stack, &$params) {
                return $this->newInstance($concrete, $stack, $params);
            }),
            'shared' => $shared
        ];
    }

    /**
     * Set closure for building from closure
     *
     * @param string $abstract
     * @param string $closure
     * @param bool $shared
     * @param bool $mutable
     */
    protected function setClosureBinding(&$abstract, &$closure, &$shared, &$mutable) {
        $this->bindings[$abstract] = [
            'make' => $this->getMakeClosure($abstract, $closure, $shared, $mutable, 'call_user_func_array'),
            'shared' => $shared
        ];
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param string $abstract
     * @param mixed $instance
     */
    public function instance($abstract, $instance) {
        $this->bindings[$abstract] = [
            'make' => function() use(&$instance) { return $instance; },
            'shared' => $instance
        ];
    }

    /**
     * Register a binding with the container.
     *
     * @param string|array $abstract // TODO: bind from array
     * @param mixed $concrete
     * @param bool $shared
     * @param bool $mutable
     */
    public function bind($abstract, $concrete = null, $shared = false, $mutable = false)
    {
        if (is_string($concrete)) {
            $this->setStringBinding($abstract, $concrete, $shared, $mutable); // return new or instance concrete
        } elseif ($concrete instanceof Closure) {
            $this->setClosureBinding($abstract, $concrete, $shared, $mutable); // return new or instance closure
        } elseif (is_null($concrete)) {
            $this->setStringBinding($abstract, $abstract, $shared, $mutable); // return new or instance abstract
        } elseif (is_object($concrete)) {
            $this->instance($abstract, $concrete);
        }
    }

    /**
     * Register a shared binding in the container.
     *
     * @param string|array $abstract
     * @param mixed $concrete
     */
    public function singleton($abstract, $concrete) {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Register a mutable singleton in the container
     *
     * @param string|array $abstract
     * @param mixed $concrete
     */
    public function mutable($abstract, $concrete) {
        $this->bind($abstract, $concrete, true, true);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array $params
     * @return mixed
     */
    protected function makeInstance(&$abstract, array &$params = []) {
//        return isset($this->bindings[$abstract]) ?
//            $this->bindings[$abstract]['make']($parameters) : $this->newInstance($abstract, $parameters);
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]['make']($params);
        } else {
            isset($this->resolved[$abstract]) ? ++$this->resolved[$abstract] : $this->resolved[$abstract] = 1;
            return $this->newInstance($abstract, $this->getStack($abstract), $params);
        }
    }

    /**
     * Wrap function under makeInstance
     *
     * @param string $abstract
     * @param array $params
     * @return mixed
     */
    public function make($abstract, array $params = []) {
        return $this->makeInstance($abstract, $params);
    }

    /**
     * Determine if a given type is shared.
     *
     * @param string $abstract
     * @return bool
     */
    public function isShared($abstract)
    {
        return !!$this->bindings[$abstract]['shared'];
    }
}
