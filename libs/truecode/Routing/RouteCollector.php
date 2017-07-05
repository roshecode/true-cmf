<?php
namespace Truecode\Routing;

class RouteCollector
{
    const DELETE    = 'DELETE';
    const GET       = 'GET';
    const HEAD      = 'HEAD';
    const OPTIONS   = 'OPTIONS';
    const PATCH     = 'PATCH';
    const POST      = 'POST';
    const PUT       = 'PUT';

    /**
     * @var RouteCollection[]
     */
    protected $routeCollections = [];

    protected $prefix = '';

    public function __construct() {
        $backtrackLimit = ini_get('pcre.backtrack_limit');
        $tail = '/' . str_repeat(' ', RouteCollection::ROUTES_CHUNK_LIMIT);
        $this->routeCollections = [
            self::DELETE    => new RouteCollection($backtrackLimit, $tail),
            self::GET       => new RouteCollection($backtrackLimit, $tail),
            self::HEAD      => new RouteCollection($backtrackLimit, $tail),
            self::OPTIONS   => new RouteCollection($backtrackLimit, $tail),
            self::PATCH     => new RouteCollection($backtrackLimit, $tail),
            self::POST      => new RouteCollection($backtrackLimit, $tail),
            self::PUT       => new RouteCollection($backtrackLimit, $tail),
        ];
    }

    static function normalize(string $route) : string {
        return '/' . trim($route, '/');
    }

    public function prefixy(string $route) : string {
        // check route is relative (without "/") or absolute (with "/")
        return $route[0] === '/' ? static::normalize($route) : $this->prefix . static::normalize($route);
    }

    /**
     * @param string $method
     * @param string $route
     * @param string|array|\Closure $handler
     */
    protected function add(string $method, string $route, $handler) {
        $this->routeCollections[$method]->add($route, $handler);
    }

    /**
     * @param string|\Closure $prefix
     * @param string|array|\Closure $group
     */
    public function prefix(string $prefix, $group) {
        $this->prefix = ($prefix[0] === '/'
            ? static::normalize($prefix)
            : $this->prefix . static::normalize($prefix));
        is_callable($group) ? $group() : call_user_func($group);
        $this->prefix = '';
    }

    /**
     * @param string $method
     * @param string $route
     *
     * @return array
     */
    public function make($method, string $route) : array {
        return $this->routeCollections[$method]->get($route);
    }

    /**
     * @param string|array $methods
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function match($methods, string $route, $handler) {
        foreach ((array) $methods as $method) {
            $this->add($method, $route, $handler);
        }
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function get(string $route, $handler) {
        $this->add(self::GET, $this->prefixy($route), $handler);
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function post(string $route, $handler) {
        $this->add(self::POST, $this->prefixy($route), $handler);
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function put(string $route, $handler) {
        $this->add(self::PUT, $this->prefixy($route), $handler);
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function patch(string $route, $handler) {
        $this->add(self::PATCH, $this->prefixy($route), $handler);
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function delete(string $route, $handler) {
        $this->add(self::DELETE, $this->prefixy($route), $handler);
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function options(string $route, $handler) {
        $this->add(self::OPTIONS, $this->prefixy($route), $handler);
    }
}
