<?php

namespace Truth\Support\Services\Locator;

use Closure;
use ReflectionClass;
use SplFixedArray;
use Truth\Support\Abstracts\Facade;

class Box
{
    const MAKE      = 0;
    const SHARE     = 1;
    const STACK     = 2;
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];
    protected $resolved = [];
    protected $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
        Facade::__register($this);
    }

    public function getInstance() {
        return $this;
    }

    /**
     * Get stack of classes and parameters for automatic building
     *
     * @param array $params
     * @return SplFixedArray|null
     */
    protected function getStack(array $params) {
        $index = -1;
        $length = count($params);
        $stack = new SplFixedArray($length);
        while ($length) {
            $stack[++$index] = $params[--$length]->getClass();
        }
        return $stack;
    }

    /**
     * Build and inject all dependencies with parameters
     *
     * @param SplFixedArray $stack
     * @param array $params
     * @return SplFixedArray
     */
    protected function build(SplFixedArray $stack, array &$params) {
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
     * Create new instance of concrete class with stack closure
     *
     * @param string $concrete
     * @param array $params
     * @return object
     *
     * @throws \Exception
     */
    public function newInstanceWithStackClosure(&$concrete, &$params, $closure) {
        if (class_exists($concrete)) {
            $reflectionClass = new ReflectionClass($concrete);
            $constructor = $reflectionClass->getConstructor();
            return ($constructor && $cParams = $constructor->getParameters()) ?
                $reflectionClass->newInstanceArgs($this->build($closure($cParams), $params)->toArray()) : new $concrete;
        } else {
            throw new \Exception('Class ' . $concrete . ' does not exist!!!'); // TODO: more pretty
        }
    }

    /**
     * Create new instance of concrete class
     *
     * @param string $concrete
     * @param array $params
     * @return object
     *
     * @throws \Exception
     */
    protected function newInstance(&$concrete, &$params) {
        return $this->newInstanceWithStackClosure($concrete, $params, function(&$cParams) {
            return $this->getStack($cParams);
        });
    }

    protected function newInstanceCached(&$abstract, &$concrete, &$params) {
        return $this->newInstanceWithStackClosure($concrete, $params, function(&$cParams) use(&$abstract) {
            $stack = &$this->bindings[$abstract][self::STACK];
            return $stack ? $stack : $stack = $this->getStack($cParams);
        });
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
            self::MAKE => $shared ?
                $mutable ?
                    function(&$params) use(&$abstract, &$concrete) {
                        $binding = &$this->bindings[$abstract];
                        $shared = &$binding[self::SHARE];
                        return $shared === true ? $shared = $this->newInstanceCached($abstract, $concrete, $params) :
                            $params ? $shared = $this->newInstanceCached($abstract, $concrete, $params) : $shared;
                    } :
                    function(&$params) use(&$abstract, &$concrete) {
                        $shared = &$this->bindings[$abstract][self::SHARE];
                        return $shared === true ? $shared = $this->newInstance($concrete, $params) : $shared;
                    } :
                function(&$params) use(&$abstract, &$concrete) {
                    return $this->newInstanceCached($abstract, $concrete, $params);
                },
            self::SHARE => &$shared
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
            self::MAKE => $shared ?
                $mutable ?
                    function(&$params) use(&$abstract, &$concrete) {
                        $shared = &$this->bindings[$abstract][self::SHARE];
                        return $shared = ($shared === true ? call_user_func_array($concrete, $params) :
                            $params ? call_user_func_array($concrete, $params) : $shared);
                    } :
                    function(&$params) use(&$abstract, &$concrete) {
                        return ($shared = &$this->bindings[$abstract][self::SHARE]) === true ?
                            $shared = call_user_func_array($concrete, $params) : $shared;
                    } :
                function(&$params) use(&$concrete) {
                    return call_user_func_array($concrete, $params);
                },
            self::SHARE => &$shared
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
            self::MAKE => function() use(&$instance) { return $instance; },
            self::SHARE => $instance
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
        if (is_null($concrete)) {
            $this->setStringBinding($abstract, $abstract, $shared, $mutable);
        } elseif (is_string($concrete)) {
            $this->setStringBinding($abstract, $concrete, $shared, $mutable);
        } elseif ($concrete instanceof Closure) {
            $this->setClosureBinding($abstract, $concrete, $shared, $mutable);
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

    public function alias($alias, $abstract) {
        $this->bindings[$alias] = &$this->bindings[$abstract];
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
            return $this->bindings[$abstract][self::MAKE]($params);
        } else {
            isset($this->resolved[$abstract]) ? ++$this->resolved[$abstract] : $this->resolved[$abstract] = 1;
            $this->bind($abstract);
            return $this->make($abstract, $params);
//            return $this->newInstance($abstract, $params);
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

    public function pack($filePath) {
        $pack = include $filePath;
        $service = $pack['service'];
        $abstract = array_keys($service)[0];
        $this->singleton($abstract, $service[$abstract]);
        $configs = $this->make($abstract, [$pack['directory'], $pack['files']]);
        $this->instance('Box', $this);

        foreach ($configs['services']['interfaces'] as $abstract => $concrete) {
            $this->bind($abstract, $concrete);
        }
        foreach ($configs['services']['singletons'] as $abstract => $concrete) {
            $this->singleton($abstract, $concrete);
        }
        foreach ($configs['services']['mutables'] as $abstract => $concrete) {
            $this->mutable($abstract, $concrete);
        }
        foreach ($configs['services']['aliases'] as $alias => $abstract) {
            $this->alias($alias, $abstract);
        }
        foreach ($configs['settings'] as $abstract => $settings) {
            $this->make($abstract, $settings)->__register($this)->boot();
        }
    }

    /**
     * Determine if a given type is shared.
     *
     * @param string $abstract
     * @return bool
     */
    public function isShared($abstract)
    {
        return !!$this->bindings[$abstract][self::SHARE];
    }

    public function __destruct()
    {
        $elapsed = (microtime(true) - $this->startTime) * 1000;
        echo "Execution time : $elapsed ms";
    }
}
