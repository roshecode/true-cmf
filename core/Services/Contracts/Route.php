<?php
namespace Core\Services\Contracts;

interface Route
{
    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function get(string $route, $handler);

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function post(string $route, $handler);

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function put(string $route, $handler);

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function patch(string $route, $handler);

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function delete(string $route, $handler);

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function options(string $route, $handler);

    /**
     * @param string|array $methods
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function match($methods, string $route, $handler);

    /**
     * @param string $method
     * @param string $route
     */
    public function make($method, string $route);

    /**
     * @param string $prefix
     * @param string|array|\Closure $group
     */
    public function prefix(string $prefix, $group);
}
