<?php
namespace T\Services\Container;

use ReflectionClass;
use ReflectionParameter;
use SplFixedArray;
use T\Abstracts\Facade;
use T\Interfaces\Container as ContainerInterface;
use T\Exceptions\FileNotFoundException;

class Box implements ContainerInterface
{
    const MAKE  = 0;
    const SHARE = 1;
    const STACK = 2;
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings;
    protected $resolved;
    protected $startTime;
    
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
                    ))));
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
        return !!$this->bindings[$abstract][self::SHARE];
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
        return ($constructor && $reflectionParams = $constructor->getParameters())
            ? $reflectionClass->newInstanceArgs($this->build($stack
                ?: $stack = $this->getStack($reflectionParams), $params)->toArray())
            : new $concrete;
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
     * @return SplFixedArray $building
     */
    protected function build(SplFixedArray $stack, array &$params) {
        $length   = count($params);
        $index    = count($stack) - 1 - $length;
        $building = new SplFixedArray($length);
        while ($length) {
            $item                = $stack[++$index];
            $building[--$length] = $item instanceof ReflectionParameter ? array_pop($params)
                : $this->makeInstance($item->name, $params);
        }
        return $building;
    }
    
    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $params
     *
     * @return mixed
     */
    protected function makeInstance(&$abstract, array &$params = []) {
        return isset($this->bindings[$abstract])
            ? $this->bindings[$abstract][self::MAKE]($params)
            : $this->create($abstract, $params);
    }
    
    public function pack($filePath) {
        $this->instance('Box', $this);
        if (file_exists($filePath)) {
            $config = include $filePath;
            foreach ($config['interfaces'/**/] as $abstract => $concrete) $this->bind($abstract, $concrete);
            foreach ($config['singletons'/**/] as $abstract => $concrete) $this->singleton($abstract, $concrete);
            foreach ($config['mutable'/*   */] as $abstract => $concrete) $this->mutable($abstract, $concrete);
            foreach ($config['aliases'/*   */] as $abstract => $concrete) $this->alias($abstract, $concrete);
            foreach ($config['settings'/*  */] as $abstract => $settings) $this->make($abstract, $settings)
                                                                               ->__register($this)->boot();
        } else throw new FileNotFoundException('File to pack not found');
    }
    
    public function __destruct() {
        $elapsed = (microtime(true) - $this->startTime) * 1000;
        echo "<br /><br /><hr />Container execution time : $elapsed ms";
    }
}
