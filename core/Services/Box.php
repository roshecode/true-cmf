<?php
namespace T\Services;

use ArrayAccess;
use ReflectionClass;
use ReflectionParameter;
use SplFixedArray;
use T\Abstracts\Facade;
use T\Interfaces\Box as ContainerInterface;
use T\Interfaces\Service;

class Box implements ContainerInterface, ArrayAccess
{
    const MAKE  = 0;
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
     */
    public function __construct() {
        $this->startTime = microtime(true);
        Facade::__register($this);
    }
    
    public function getInstance() {
        return $this;
    }
    
    protected function error() {
        throw new \Exception('ContextualBindingException');
    }
    
    /**
     * {@inheritdoc}
     */
    public function bind($abstract, $concrete = null, $shared = false, $mutable = false) {
        (is_null($concrete) && class_exists($abstract)
            ? $this->setStringBinding($abstract, $abstract, $shared, $mutable)
            : (is_string($concrete) && class_exists($concrete) && !is_callable($concrete)
                ? $this->setStringBinding($abstract, $concrete, $shared, $mutable)
                : (is_callable($concrete)
                    ? $this->setClosureBinding($abstract, $concrete, $shared, $mutable)
                    : (is_string($abstract)
                        ? $this->instance($abstract, $concrete)
                        : $this->error()
                    )
                )
            )
        );
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
        $placeholder             = &$this->bindings[$abstract];
        $placeholder             = new SplFixedArray(1);
        $placeholder[self::MAKE] = function () use ($instance) { return $instance; };
    }
    
    /**
     * {@inheritdoc}
     */
    public function alias($alias, $abstract) {
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
    protected function makeInstance(string &$abstract, array &$params = []) {
        return isset($this->bindings[$abstract])
            ? $this->bindings[$abstract][self::MAKE]($params)
            : $this->create($abstract, $params);
    }
    
    /**
     * {@inheritdoc}
     */
    public function make($abstract, array $params = []) {
        return $this->makeInstance($abstract, $params);
    }
    
    /**
     * {@inheritdoc}
     */
    public function create($abstract, array $params = []) {
        isset($this->resolved[$abstract]) ? ++$this->resolved[$abstract] : $this->resolved[$abstract] = 1; // statistic
        $this->bind($abstract);
        return $this->make($abstract, $params);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isShared($abstract) {
        return isset($this->bindings[$abstract]) && !!$this->bindings[$abstract][self::SHARE];
    }
    
    /**
     * Set closure for building from string
     *
     * @param string $abstract
     * @param string $concrete
     * @param bool   $shared
     * @param bool   $mutable
     */
    protected function setStringBinding(&$abstract, &$concrete, &$shared, &$mutable) {
        $placeholder = &$this->bindings[$abstract];
        $placeholder = [
            self::MAKE  => $shared
                ? $mutable
                    ? function (&$params) use (&$placeholder, &$abstract, &$concrete) {
                        $shared = &$placeholder[self::SHARE];
                        return $shared && !$params ? $shared
                            : $shared = $this->newInstance($concrete, $params, $placeholder[self::STACK]);
                    }
                    : function (&$params) use (&$placeholder, &$abstract, &$concrete) {
                        return $placeholder[self::SHARE]
                            ?: $placeholder[self::SHARE] = $this->newInstance($concrete, $params);
                    }
                : function (&$params) use (&$placeholder, &$concrete) {
                    return $this->newInstance($concrete, $params, $placeholder[self::STACK]);
                },
            self::SHARE => false];
    }
    
    /**
     * Set closure for building from closure
     *
     * @param string $abstract
     * @param string $concrete
     * @param bool   $shared
     * @param bool   $mutable
     */
    protected function setClosureBinding(&$abstract, &$concrete, &$shared, &$mutable) {
        $placeholder = &$this->bindings[$abstract];
        $placeholder = [
            self::MAKE  => $shared
                ? $mutable
                    ? function (&$params) use (&$placeholder, &$abstract, &$concrete) {
                        $placeholder = &$placeholder[self::SHARE];
                        return $placeholder && !$params ? $placeholder
                            : $placeholder = call_user_func_array($concrete, $params);
                    }
                    : function (&$params) use (&$placeholder, &$abstract, &$concrete) {
                        return $placeholder[self::SHARE]
                            ?: $placeholder[self::SHARE] = call_user_func_array($concrete, $params);
                    }
                : function (&$params) use (&$concrete) {
                    return call_user_func_array($concrete, $params);
                },
            self::SHARE => false];
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
    protected function newInstance(&$concrete, &$params, &$stack = null) {
        $reflectionClass = new ReflectionClass($concrete);
        $constructor     = $reflectionClass->getConstructor();
        $instance = ($constructor && $reflectionParams = $constructor->getParameters())
            ? $reflectionClass->newInstanceArgs($this->build($stack
                ?: $stack = $this->getStack($reflectionParams), $params))
            : new $concrete;
        return $instance instanceof Service ? $instance->__register($this) : $instance;
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
                    : $this->makeInstance($item->name, $params)
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

    public function packScope(array $config, \Closure $method) {
        foreach ($config as $abstract => $params) {
            if (is_array($params)) {
                $concrete = $params[self::CONCRETE];
                if (isset($params[self::ALIAS])) {
                    $this->alias($params[self::ALIAS], $abstract);
                }
                $method($abstract, $concrete,
                    isset($params[self::ARGUMENTS]) ? $params[self::ARGUMENTS] : []);
            } else {
                $method($abstract, $params);
            }
        }
    }

    protected function packScopeFromSelf($scope, \Closure $method) {
        if (isset($this->config[$scope])) {
            $this->packScope($this->config[$scope], $method);
        }
    }
    
    public function pack(array $config) {
        $this->config = $config;
        $this->packScopeFromSelf(self::SCOPE_INSTANCES, function ($abstract, $concrete) {
            $this->instance($abstract, $concrete);
        });
        $this->packScopeFromSelf(self::SCOPE_INTERFACES, function ($abstract, $concrete) {
            $this->bind($abstract, $concrete);
        });
        $this->packScopeFromSelf(self::SCOPE_MUTABLE, function ($abstract, $concrete, $arguments) {
            $this->mutable($abstract, $concrete);
            $this->makeInstance($abstract, $arguments)->__boot();
        });
        $this->packScopeFromSelf(self::SCOPE_SINGLETONS, function ($abstract, $concrete, $arguments) {
            $this->singleton($abstract, $concrete);
            $this->makeInstance($abstract, $arguments)->__boot();
        });
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
