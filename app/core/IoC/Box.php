<?php

namespace Truth\IoC;

use Closure;
use BadMethodCallException;
use Truth\Support\Facades\Lang;

class StrictBox {
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];
    /*
     * ['abstract string' => [
     *      [
     *          'concrete' => Closure,
     *          'shared' => true|false
     *      ]
     * ]]
     */
    // Здесь хранятся только синглтоны
    protected $instances = [];

    public function bind($abstract, $concrete = null, $share = false)
    {
        /*
         * When you add a service, you should register it
         * with its interface or with a string that you can use
         * in the future even if you will change the service implementation.
         */

        if (is_object($concrete) && $share) {
            $this->instances[$abstract] = $concrete;
        }
        $this->services[$abstract] = (is_object($concrete) ? get_class($concrete) : $concrete);
        $this->shared[$abstract] = $share;
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  mixed   $instance
     * @return void
     */
    public function instance($abstract, $instance) {
        $this->instances[$abstract] = $instance;
    }

    /**
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = []) {
        // Если абстракция - синглтон и он уже создан - возвращаем его
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        // Если абстракция - синглтон и он ещё не сохранён (о чём говорит проверка в инстансах выше),
        // то сохраняем его в инстансы для последующего извлечения (кэшируем)
        if ($this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = null; //$object
        }
    }

    /**
     * Determine if a given type is shared.
     *
     * @param  string  $abstract
     * @return bool
     */
    public function isShared($abstract)
    {
        return isset($this->instances[$abstract]) ? true :
            isset($this->bindings[$abstract]['shared']) ? $this->bindings[$abstract]['shared'] : false;
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
     * @param  mixed  $service
     * @return mixed
     */
    protected function fix(&$service)
    {
//        $service = is_string($service) ? ltrim($service, '\\') : $service;
        $service = ltrim($service, '\\');
    }

    public function bind() {
        print_r($this->bindings);
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