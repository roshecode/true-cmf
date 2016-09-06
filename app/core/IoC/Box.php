<?php

namespace Truth\IoC;

use Closure;
use BadMethodCallException;
use Truth\Support\Facades\Lang;

class StrictBox {
    protected static $instance;
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];
    /*
     * ['abstract string' => [
     *      [
     *          'concrete' => String|Closure|NULL,
     *          'shared' => Object|false
     *      ]
     * ]]
     */
    /**
     * The stack of concretions currently being built.
     *
     * @var array
     */
    protected $buildStack = [];

    public function __construct()
    {
        self::$instance = $this;
    }

    protected function getStack(&$class, array &$stack = []) {
        $constructor = (new \ReflectionClass($class))->getConstructor();
        if($params = $constructor ? $constructor->getParameters() : false) {
            foreach ($params as $index => $param) {
                $nextClass = $param->getClass();
                if ($nextClass) {
                    $nextClassName = $nextClass->name;
                    $stack[$nextClassName] = $this->getStack($nextClassName);
//                    $nextStack = $this->getStack($nextClassName);
//                    if (! $nextStack) {
//                        $stack[] = $nextClassName;
//                    }
                } else {
                    $stack[] = null;
                }
            }
        };
        return $stack;
    }

    protected function build(&$stack, &$params) {
        foreach ($stack as $class => $nextStack) {
            if ($nextStack) { // if we have dependency from another class
//                $stack[$class] = $this->make($class, $this->build($nextStack, $params));
                $stack[$class] = $this->newInstance($class, $this->build($nextStack, $params));
            } elseif (is_string($class)) { // if item is class
//                $stack[$class] = $this->makeRef($class, $params); // TODO: array
                $stack[$class] = $this->newInstance($class, $params);

//                $stack[$class] = $this->bindings[$class]['make']($params); // MAIN: CREATE ANY CLASS INSTANCE
            } else { // if item is scalar
                $stack[$class] = array_shift($params);
            }
        }
        return $stack;
    }

    protected function newInstance(&$concrete, &$params) {
//        return $params ?
//            (new \ReflectionClass($concrete))->newInstanceArgs(
//                $this->build($this->getStack($concrete), $params)) :
//            new $concrete;
        if ($params) {
            return (new \ReflectionClass($concrete))->newInstanceArgs($params);
        } else {
            return new $concrete;
        }
    }

    protected function getNonSharedClosure(&$concrete) {
        return function(&$params) use($concrete) {
            $params = $this->build($this->getStack($concrete), $params);
            return $this->newInstance($concrete, $params);
        };
    }

    protected function getSharedClosure(&$abstract, &$concrete) {
        return function(&$params) use($abstract, $concrete) {
            $shared = &$this->bindings[$abstract]['shared'];
            $params = $this->build($this->getStack($concrete), $params);
            return $shared === true ? $shared = $this->newInstance($concrete, $params) : $shared;
//            $abstractReference = &$this->bindings[$abstract];
//            return isset($abstractReference['shared']) ?
//                $abstractReference['shared'] :
//                $abstractReference['shared'] = $shared = $this->newInstance($concrete, $params);
        };
    }

    protected function setStringBinding(&$abstract, &$concrete, &$share) {
        $this->bindings[$abstract] = [
            'make' => $share ?
                $this->getSharedClosure($abstract, $concrete) : $this->getNonSharedClosure($concrete),
            'shared' => $share
        ];
    }

    protected function setClosureBinding($abstract, $closure, $share) {
        $this->bindings[$abstract] = [
            'make' => function() use($abstract, $closure) {
                $shared = &$this->bindings[$abstract]['shared'];
                return $shared === true ? $shared = $closure(self::$instance) : $shared;
            },
            'shared' => $share
        ];
    }

    public function bind($abstract, $concrete = null, $share = false)
    {
        if (is_null($concrete)) {
            $this->setStringBinding($abstract, $abstract, $share); // return new or instance abstract
        } elseif (is_string($concrete)) {
            $this->setStringBinding($abstract, $concrete, $share); // return new or instance concrete
//        } elseif (is_callable($concrete)) {
        } elseif ($concrete instanceof Closure) {
            $this->setClosureBinding($abstract, $concrete, $share); // return new or instance closure
        } elseif (is_object($concrete)) {
            $this->instance($abstract, $concrete);
        }
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
            'concrete' => function() use($instance) { return $instance; },
            'shared' => $instance
        ];
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
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = []) {
        $abstractReference = &$this->bindings[$abstract];
        if (! isset($abstractReference)) {
            $this->bind($abstract);
//            return $this->newInstance($abstract, $parameters);
        }
//        return $abstractReference['make']($parameters);
        return $abstractReference['make']($parameters);
    }

    protected function makeRef($abstract, array &$parameters) {
        $abstractReference = &$this->bindings[$abstract];
        if (! isset($abstractReference)) {
            $this->bind($abstract);
        }
        return $abstractReference['make']($parameters);
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

    protected function test($str) {
        echo $str;
    }
}

/**
 * Class Box
 *
 * @method test(string $str)
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
