<?php
namespace Truecode\Routing;

class Route
{
    const DELETE    = 'DELETE';
    const GET       = 'GET';
    const HEAD      = 'HEAD';
    const OPTIONS   = 'OPTIONS';
    const PATCH     = 'PATCH';
    const POST      = 'POST';
    const PUT       = 'PUT';

    private const ROUTES_CHUNK_LIMIT = 100;
    private const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    private $backtrackLimit;

    protected $routes = [];
    protected $count  = -1;
    protected $tail   = '/';

    public function __construct() {
        $this->backtrackLimit = ini_get('pcre.backtrack_limit');
        $this->tail .= str_repeat(' ', self::ROUTES_CHUNK_LIMIT - 1);
    }
    
    protected function add($method, $route, $handler) {
        $route = $route[0] == '/' ? $route : '/' . $route;
        $rest  = ++$this->count % self::ROUTES_CHUNK_LIMIT;
        $regex = preg_replace_callback(self::ROUTES_SPLIT_REGEX, function ($matches) {
                $node = &$matches[0];
                if ($node[0] == ':') {
                    $parts_regexp = explode(':', $node, 3);
                    return isset($parts_regexp[2]) ? '(' . $parts_regexp[2] . ')' : '([^/]+)';
                }
                return $node;
            }, $route) . '/( {' . ($rest + 1) . '})';
        $route = &$this->routes[$method][($this->count - $rest) / self::ROUTES_CHUNK_LIMIT];
        $rest
            ? $route[0] = $regex . '|' . $route[0]
            : $route = \SplFixedArray::fromArray([$regex, new \SplFixedArray(self::ROUTES_CHUNK_LIMIT)]);
        $route[1][$rest] = $handler;
    }
    
    public function match($methods, $route, $handler) {
        if (is_array($methods))
            foreach ($methods as $method)
                $this->add(strtoupper($method), $route, $handler);
        else $this->add(strtoupper($methods), $route, $handler);
    }
    
    public function make($method, $uri) {
        $uri .= $this->tail;
        $matches = [];
        $routes  = &$this->routes[strtoupper($method)];
        for ($i = count($routes) - 1; $i >= 0; --$i) {
            if (preg_match("~^(?|{$routes[$i][0]})~", $uri, $matches)) {
//                unset($matches[0]);
                array_shift($matches);
                return [$routes[$i][1][strlen(array_pop($matches)) - 1], &$matches];
            }
        }
        return [function() {echo 'Not found';}, [404]];
    }

    public function get($route, $handler) {
        $this->add(self::GET, $route, $handler);
    }

    public function post($route, $handler) {
        $this->add(self::POST, $route, $handler);
    }

    public function put($route, $handler) {
        $this->add(self::PUT, $route, $handler);
    }

    public function patch($route, $handler) {
        $this->add(self::PATCH, $route, $handler);
    }

    public function delete($route, $handler) {
        $this->add(self::DELETE, $route, $handler);
    }

    public function options($route, $handler) {
        $this->add(self::OPTIONS, $route, $handler);
    }
}
