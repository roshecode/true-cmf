<?php
namespace T\Interfaces;

interface Route extends Service
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
     * @param string|\Closure $prefixOrHandler
     * @param string|\Closure $handler
     */
    public function group($prefixOrHandler, $handler);

    /**
     * @param string|array $methods
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function make($methods, string $route, $handler);
}
