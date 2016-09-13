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
     * @return SplFixedArray|null
     */
    protected function getStack(&$class) {

        $constructor = (new ReflectionClass($class))->getConstructor();
        if($constructor) {
            $params = $constructor->getParameters();
            $index = -1;
            $length = count($params);
            $stack = new SplFixedArray($length);
            while ($length) {
                $stack[++$index] = $params[--$length]->getClass();
            }
            return $stack;
        };
        return null;
    }

    /**
     * Build and inject all dependencies with parameters
     *
     * @param SplFixedArray|null $stack
     * @param array $params
     * @return SplFixedArray
     */
    protected function build($stack, array &$params) {
        $index = 0;
        $length = count($stack);
        $built = new SplFixedArray($length);
        while ($index < $length) {
            $item = $stack[$index];
            $built[$length - ++$index] = $item ? $this->makeInstance($item->name, $params) : array_pop($params);
        }
        return $built;
    }

    /**
     * Create new instance of concrete class
     *
     * @param string $concrete
     * @param SplFixedArray|null $stack
     * @param array $params
     * @return object
     *
     * @throws \Exception
     */
    protected function newInstance(&$concrete, $stack, array &$params) {
        if (class_exists($concrete)) {
                // TODO: use commented line for PHP >=5.4 && < 5.6 version
//            return $stack ? (new ReflectionClass($concrete))->newInstanceArgs($this->build($stack, $params)->toArray()) :
//                 : new $concrete;
            return $stack ? new $concrete(...$this->build($stack, $params)->toArray()) : new $concrete;
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
            'make' => $this->getMakeClosure($abstract, $concrete, $shared, $mutable,
                function (&$concrete, $stack, &$params) {
                return $this->newInstance($concrete, $stack, $params);
            }),
            'shared' => &$shared
        ];
    }

    /**
     * Set closure for building from closure
     *
     * @param string $abstract
     * @param string $concrete
     * @param bool $shared
     * @param bool $mutable
     */
    protected function setClosureBinding(&$abstract, &$concrete, &$shared, &$mutable) {
        $this->bindings[$abstract] = [
            'make' => $shared ?
                $mutable ?
                    function(&$params) use(&$abstract, &$concrete) {
                        $shared = &$this->bindings[$abstract]['shared'];
                        return $shared = ($shared === true ? call_user_func_array($concrete, $params) :
                            $params ? call_user_func_array($concrete, $params) : $shared);
                    } :
                    function(&$params) use(&$abstract, &$concrete) {
                        return ($shared = &$this->bindings[$abstract]['shared']) === true ?
                            $shared = call_user_func_array($concrete, $params) : $shared;
                    } :
                function(&$params) use(&$concrete) {
                    return call_user_func_array($concrete, $params);
                },
            'shared' => &$shared
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
            $this->instance($abstract, $concrete); // return instance
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
