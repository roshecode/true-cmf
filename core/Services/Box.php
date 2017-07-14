<?php
namespace T\Services;

use ArrayAccess;
use ReflectionClass;
use ReflectionParameter;
use SplFixedArray;
use T\Abstracts\Facade;
use T\Interfaces\BoxInterface;
use T\Interfaces\ServiceInterface;
//use T\Traits\Service;

class Box implements BoxInterface, ArrayAccess
{
    //use Service;

    const PROXY = 0;
    const SHARE = 1;
    const STACK = 2;

    const ALIAS     = 'alias';
    const CONCRETE  = 'bind';
    const ARGUMENTS = 'arguments';

    const SCOPE_INSTANCES   = 'instances';
    const SCOPE_INTERFACES  = 'interfaces';
    const SCOPE_MUTABLE     = 'mutable';
    const SCOPE_SINGLETONS  = 'singletons';

    private $config;

    /**
     * The container's bindings.
     *
     * @var array
     */
    public $bindings = [];

    public $resolved = [];

    public $startTime;
    
    /**
     * Box constructor.
     *
     * @param array|null $config
     */
    public function __construct(array $config = null) {
        $this->startTime = microtime(true);
        Facade::__register($this);
        $this->instance(BoxInterface::class, $this);
        if ($config) {
            $this->pack($config);
        }
    }
    
    protected function error() {
        throw new \Exception('ContextualBindingException');
    }

    /**
     * Register a binding with the container.
     *
     * @param string|array               $abstract // TODO: bind from array
     * @param string|\Closure|Object|null $concrete
     * @param bool                       $shared
     * @param bool                       $mutable
     *
     * @throws \Exception
     */
    public function bind($abstract, $concrete = null, $shared = false, $mutable = false) {
        $concrete = $concrete ?: $abstract;
        $placeholder = &$this->bindings[$abstract];
        $placeholder = [
            self::PROXY  => $shared
                ? $mutable
                    ? function (&$params) use (&$concrete, &$placeholder) {
                        $placeholder = &$placeholder[self::SHARE];
                        return $placeholder && !$params
                            ? $placeholder
                            : $placeholder = $this->proxyMake($concrete, $params, $placeholder);
                    }
                    : function (&$params) use (&$abstract, &$concrete, &$placeholder) {
                        return $placeholder[self::SHARE]
                            ?: $placeholder[self::SHARE] = $this->proxyMake($concrete, $params, $placeholder);
                    }
                : function (&$params) use (&$concrete, &$placeholder) {
                    return $this->proxyMake($concrete, $params, $placeholder);
                },
            self::SHARE => false
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function singleton($abstract, $concrete) {
        $this->bind($abstract, $concrete, true);
    }
    
    /**
     * {@inheritdoc}
     */
    public function mutable($abstract, $concrete) {
        $this->bind($abstract, $concrete, true, true);
    }
    
    /**
     * {@inheritdoc}
     */
    public function instance($abstract, $instance) {
        $placeholder = &$this->bindings[$abstract];
        $placeholder = new SplFixedArray(1);
        $placeholder[self::PROXY] = function () use ($instance) { return $instance; };
    }
    
    /**
     * {@inheritdoc}
     */
    public function alias(string $alias, $abstract) {
        $this->bindings[$alias] = &$this->bindings[$abstract];
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $params
     *
     * @return mixed
     */
    protected function bindAndMakeRecursion(string &$abstract, array &$params = []) {
        if (! isset($this->bindings[$abstract])) {
            isset($this->resolved[$abstract])
                ? ++$this->resolved[$abstract]
                : $this->resolved[$abstract] = 1; // statistic
            $this->bind($abstract);
        }

        return $this->bindings[$abstract][self::PROXY]($params);
    }

    /**
     * Make closure result
     *
     * @param $concrete
     * @param $params
     * @param $placeholder
     *
     * @return mixed|object
     */
    protected function proxyMake(&$concrete, &$params, &$placeholder) {
        if (is_string($concrete) && class_exists($concrete)) {
            return $this->newInstance($concrete, $params, $placeholder[self::STACK]);
        }
        if (is_callable($concrete)) {
            return call_user_func_array($concrete, $params);
        }

        return $placeholder[self::SHARE] = $concrete;
    }

    /**
     * @param string $abstract
     * @param array $params
     *
     * @return mixed
     */
    public function make($abstract, array $params = []) {
        return $this->bindAndMakeRecursion($abstract, $params);
    }

    /**
     * @param string $abstract
     *
     * @return bool
     */
    public function isShared($abstract) {
        return isset($this->bindings[$abstract]) && !!$this->bindings[$abstract][self::SHARE];
    }
    
    /**
     * Create new instance of concrete class
     *
     * @param string     $concrete
     * @param array      $params
     * @param array|null $stack
     *
     * @return object
     * @throws \Exception
     */
    protected function newInstance(/*string*/&$concrete, &$params, &$stack = null) {
        $reflectionClass = new ReflectionClass($concrete);
        $constructor     = $reflectionClass->getConstructor();
        $instance = ($constructor && $reflectionParams = $constructor->getParameters())
            ? $reflectionClass->newInstanceArgs($this->build($stack
                ?: $stack = $this->getStack($reflectionParams), $params))
            : new $concrete;
        return $instance instanceof ServiceInterface ? $instance->__register($this) : $instance;
    }
    
    /**
     * Get stack of classes and parameters for automatic building
     *
     * @param array $params
     *
     * @return SplFixedArray|null $stack
     */
    protected function getStack(array $params) {
        $index  = -1;
        $length = count($params);
        $stack  = new SplFixedArray($length);
        while ($length) $stack[++$index] = $params[--$length]->getClass() ?: $params[$length];
        return $stack;
    }
    
    /**
     * Build and inject all dependencies with parameters
     *
     * @param SplFixedArray $stack
     * @param array         $params
     *
     * @return array $building
     */
    protected function build(SplFixedArray $stack, array &$params) {
        $stackLength = count($stack);
        $building = [];
        while ($stackLength) {
            $item = $stack[--$stackLength];
            $item instanceof ReflectionClass
                ? $building[] = $this->isShared($item->name)
                    ? $this->bindings[$item->name][self::SHARE]
                    : $this->bindAndMakeRecursion($item->name, $params)
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

    protected function packScope($scope, \Closure $method) {
        if (isset($this->config[$scope])) {
            foreach ($this->config[$scope] as $abstract => $paramsOrConcrete) {
                if (is_array($paramsOrConcrete)) {
                    $concrete = $paramsOrConcrete[self::CONCRETE] ?? $abstract;
                    if (isset($paramsOrConcrete[self::ALIAS])) {
                        $this->alias($paramsOrConcrete[self::ALIAS], $abstract);
                    }
                    $method($abstract, $concrete, $paramsOrConcrete[self::ARGUMENTS] ?? null);
                } else {
                    $method($abstract, $paramsOrConcrete);
                }
            }
        }
    }
    
    public function pack(array $config) {
        $this->config = $config;
        /**
         * @var ServiceInterface[] $bootServices
         */
        $bootServices = [];
        $this->packScope(self::SCOPE_INSTANCES, function ($abstract, $concrete) {
            $this->instance($abstract, $concrete);
        });
        $this->packScope(self::SCOPE_INTERFACES, function ($abstract, $concrete) {
            $this->bind($abstract, $concrete);
        });
        $this->packScope(self::SCOPE_MUTABLE, function (
            $abstract, $concrete, $arguments = null
        ) use(&$bootServices) {
            $this->mutable($abstract, $concrete);
            if ($arguments) {
                $bootService = $this->bindAndMakeRecursion($abstract, $arguments);//->__boot();
                if ($bootService instanceof ServiceInterface) {
                    $bootServices[] = $bootService;
                }
            }
        });
        $this->packScope(self::SCOPE_SINGLETONS, function (
            $abstract, $concrete, $arguments = []
        ) use(&$bootServices) {
            $this->singleton($abstract, $concrete);
            $bootService = $this->bindAndMakeRecursion($abstract, $arguments);//->__boot();
            if ($bootService instanceof ServiceInterface) {
                $bootServices[] = $bootService;
            }
        });
        foreach ($bootServices as $service) {
            $service->__boot();
        }
        unset($bootServices);
    }
    
//    public function __destruct() {
//        $elapsed = (microtime(true) - $this->startTime) * 1000;
//        echo "<br /><br /><hr />Container execution time : $elapsed ms";
//    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->bindings[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $abstract <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($abstract)
    {
        return $this->make($abstract);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $abstract <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $concrete <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($abstract, $concrete)
    {
        $this->singleton($abstract, $concrete);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}
