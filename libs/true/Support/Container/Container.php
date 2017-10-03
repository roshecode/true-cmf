<?php

namespace True\Support\Container;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;
use SplFixedArray;
use True\Standards\Container\{
    AbstractContainer,
    AbstractFacade,
    ContainerInterface,
    BootableInterface,
    KeyEnum,
    ScopeEnum
};

class Container extends AbstractContainer
{
    const PROXY = 0;
    const SHARE = 1;
    const STACK = 2;
    const ARGS = 3;

    protected $proxy;

    private $config;

    public $resolved = [];

    public $startTime;

    /**
     * Box constructor.
     *
     * @param array|string|null $config
     * @throws Exception
     */
    public function __construct($config = null)
    {
        $this->startTime = microtime(true);

        AbstractFacade::registerContainer($this);
        $this->instance(ContainerInterface::class, $this);

        if ($this->isBootable($this)) {
            /** @var BootableInterface $this */
            $this->__boot();
        }

        if ($config) {
            if (is_string($config)) {
                if (file_exists($config)) {
                    $config = include_once $config;
                } else {
                    throw new Exception('Services configuration file not found');
                }
            }

            $this->init($config);
        }
    }

    public function bind(string $abstract, $concrete = null) // TODO: bind from array
    {
        $binding = &$this->bindings[$abstract];
        $binding = [
            self::PROXY => $this->getMakeClosure($binding, $abstract, $concrete),
            self::SHARE => false,
        ];
    }

    public function singleton(string $abstract, $concrete = null, ?array $arguments = null)
    {
        $binding = &$this->bindings[$abstract];
        $make = $this->getMakeClosure($binding, $abstract, $concrete);
        $binding = [
            self::PROXY => function (&$params) use (&$make, &$binding) {
                return $binding[self::SHARE] ?: $binding[self::SHARE] = $make($params);
            },
            self::SHARE => false,
        ];
        $binding[self::ARGS] = $arguments;
    }

    public function mutable(string $abstract, $concrete = null, ?array $arguments = null)
    {
        $binding = &$this->bindings[$abstract];
        $make = $this->getMakeClosure($binding, $abstract, $concrete);
        $binding = [
            self::PROXY => function (&$params) use (&$make, &$binding) {
                return ($shared = &$binding[self::SHARE]) && ! $params
                    ? $shared
                    : $shared = $make($params);
            },
            self::SHARE => false,
        ];
        $binding[self::ARGS] = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function instance(string $abstract, $instance)
    {
        $placeholder = &$this->bindings[$abstract];
        $placeholder[self::PROXY] = $this->getInstanceClosure($placeholder, $instance);
    }

    /**
     * {@inheritdoc}
     */
    public function alias(string $abstract, string $alias)
    {
        $this->bindings[$alias] = &$this->bindings[$abstract];
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $params
     * @return mixed
     */
    protected function bindAndMake(string &$abstract, ?array &$params)
    {
        if (! isset($this->bindings[$abstract])) {
            isset($this->resolved[$abstract])
                ? ++$this->resolved[$abstract]
                : $this->resolved[$abstract] = 1; // statistic
            $this->bind($abstract);
        }

        // make an instance
        $params = $params ?? $this->bindings[$abstract][self::ARGS] ?? [];
        $instance = $this->bindings[$abstract][self::PROXY]($params);

        return $instance;
    }

    protected function getInstanceClosure(&$placeholder, &$concrete)
    {
        return function () use (&$placeholder, &$concrete) {
            return $placeholder[self::SHARE] = $concrete;
        };
    }

    protected function getMakeClosure(&$binding, &$abstract, &$concrete)
    {
        $concrete = $concrete ?: $abstract;

        // if $concrete is a string and represent an existed class
        if (is_string($concrete) && class_exists($concrete)) {
            return function (&$params) use (&$concrete, &$binding) {
                $instance = $this->create($concrete, $params, $binding[self::STACK]);

                if ($this->isBootable($instance)) {
                    $instance->__boot(); // TODO: resolve DI
                }

                return $instance;
                // TODO: add is_callable instance check
                // TODO: add is_bootable instance check
            };
        }

        // if $concrete is callable
        if (is_callable($concrete)) {
            return function (&$params) use (&$concrete, &$binding) {
                return $this->invoke($concrete, $params, $binding[self::STACK]);
            };
        }

        // if $concrete is an instance
        return $this->getInstanceClosure($placeholder, $concrete);
    }

    /**
     * @param string $abstract
     * @param array  $params
     * @return mixed
     */
    public function make(string $abstract, ?array $params = null)
    {
        return $this->bindAndMake($abstract, $params);
    }

    public function isBootable($instance) : bool
    {
        return $instance instanceof BootableInterface;
    }

    /**
     * @param string $abstract
     * @return bool
     */
    public function isShared(string $abstract) : bool
    {
        return isset($this->bindings[$abstract]) && ! ! $this->bindings[$abstract][self::SHARE];
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        string &$concrete,
        array &$params,
        ?array &$stack = null
    ) {
        $reflectionClass = new ReflectionClass($concrete);
        $constructor = $reflectionClass->getConstructor();

        return ($constructor && $reflectionParams = $constructor->getParameters())
            ? $reflectionClass->newInstanceArgs(
                $this->build($stack ?: $stack = $this->getStack($reflectionParams), $params)
            )
            : new $concrete;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(
        callable $callable,
        array &$params,
        ?array &$stack = null
    ) {
        $reflectionFunction = new ReflectionFunction($callable);

        return ($reflectionParams = $reflectionFunction->getParameters())
            ? $reflectionFunction->invokeArgs(
                $this->build($stack ?: $stack = $this->getStack($reflectionParams), $params)
            )
            : call_user_func_array($callable, $params);
    }

    /**
     * @param array|callable $callable
     * @param array          $params
     */
    public function call($callable, array $params)
    {
        if (is_array($callable)) {
            $instance = $this->bindAndMake($callable[0], $params);
        }
    }

    /**
     * Get stack of classes and parameters for automatic building
     *
     * @param array $params
     * @return SplFixedArray|null $stack
     */
    protected function getStack(array $params)
    {
        $index = -1;
        $length = count($params);
        $stack = new SplFixedArray($length);
        while ($length) {
            $stack[++$index] = $params[--$length]->getClass() ?: $params[$length];
        }

        return $stack;
    }

    /**
     * Build and inject all dependencies with parameters
     *
     * @param SplFixedArray $stack
     * @param array         $params
     * @return array $building
     */
    protected function build(SplFixedArray $stack, array &$params)
    {
        $stackLength = count($stack);
        $building = [];
        while ($stackLength) {
            $item = $stack[--$stackLength];
            $item instanceof ReflectionClass
                ? $building[] = $this->isShared($item->name)
                ? $this->bindings[$item->name][self::SHARE]
                : $this->bindAndMake($item->name, $params)
                : empty($params) ?: $building[] = array_shift($params);
        }

        return $building;

//        $length   = count($params);
//        $index    = count($stack) - 1 - $length;
//        $building = new SplFixedArray($length);
//        while ($length) {
//            $item                = $stack[++$index];
//            $building[--$length] = $item instanceof ReflectionParameter ? array_pop($params)
//                : $this->makeInstance($item->name, $params);
//        }
//        return $building;
    }

    protected function packScope($scope, \Closure $method)
    {
        if (isset($this->config[$scope])) {
            foreach ($this->config[$scope] as $abstract => $paramsOrConcrete) {
                if (is_array($paramsOrConcrete)) {
                    $concrete = $paramsOrConcrete[KeyEnum::Concrete] ?? $abstract;
                    if (isset($paramsOrConcrete[KeyEnum::Alias])) {
                        $this->alias($paramsOrConcrete[KeyEnum::Alias], $abstract);
                    }
                    $method($abstract, $concrete, $paramsOrConcrete[KeyEnum::Arguments]);
                } else {
                    $method($abstract, $paramsOrConcrete);
                }
            }
        }
    }

    public function init(array $config)
    {
        $this->config = $config;
        $this->packScope(ScopeEnum::Instantiated, function ($abstract, $concrete) {
            $this->instance($abstract, $concrete);
        });
        $this->packScope(ScopeEnum::Disposable, function ($abstract, $concrete) {
            $this->bind($abstract, $concrete);
        });
        $this->packScope(ScopeEnum::Mutable, function ($abstract, $concrete, $params = []) {
            $this->mutable($abstract, $concrete, $params);
        });
        $this->packScope(ScopeEnum::Single, function ($abstract, $concrete, $params = []) {
            $this->singleton($abstract, $concrete, $params);
        });
    }

//    public function __destruct() {
//        $elapsed = (microtime(true) - $this->startTime) * 1000;
//        echo "<br /><br /><hr />Container execution time : $elapsed ms";
//    }
}
