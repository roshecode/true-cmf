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

    protected const REGEX_DELIMITER = ':';
    protected const VARIABLE_DELIMITER = ':';
    protected const ROUTES_CHUNK_LIMIT = 96;
    protected const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    private $backtrackLimit; // TODO: add mechanism for regex length limiting

    protected $routes = [];
//    protected $count  = -1;
    protected $counts  = [
        self::DELETE    => 0,
        self::GET       => 0,
        self::HEAD      => 0,
        self::OPTIONS   => 0,
        self::PATCH     => 0,
        self::POST      => 0,
        self::PUT       => 0
    ];
    protected $tail;

    public function __construct() {
        $this->backtrackLimit = ini_get('pcre.backtrack_limit');
        $this->tail = '/' . str_repeat(' ', static::ROUTES_CHUNK_LIMIT);
    }
    
    protected function add($method, $route, $handler) {
//        $hash = crc32($route);
        $route[0] == '/' ?: $route = "/$route"; // TODO: check if absolute / relative route
        $count = &$this->counts[$method];
        $rest  = $count % static::ROUTES_CHUNK_LIMIT;
        $regex = preg_replace_callback(static::ROUTES_SPLIT_REGEX, function ($matches) {
                $node = &$matches[0];
                if ($node[0] == static::VARIABLE_DELIMITER) {
                    $regexp_parts = explode(static::REGEX_DELIMITER, $node, 3);
                    return count($regexp_parts) > 2 ? '(' . $regexp_parts[2] . ')' : '([^/]+)';
                }
                return $node;
            }, $route) . "/( {{$rest}})";
        $route = &$this->routes[$method][(++$count - $rest) / static::ROUTES_CHUNK_LIMIT];
        $rest
//            ? $route[0] = "$regex|$route[0]"
            ? $route[0] .= "|$regex"
            : $route = \SplFixedArray::fromArray([
                $regex,
                new \SplFixedArray(static::ROUTES_CHUNK_LIMIT)
            ], false);
        $route[1][$rest] = $handler;
    }
    
    public function match($methods, $route, $handler) {
        foreach ((array) $methods as $method) {
            $this->add($method, $route, $handler);
        }
    }
    
    public function make($method, $uri) {
        $matches = [];
        $routes  = &$this->routes[$method];
//        for ($i = count($routes) - 1; $i >= 0, $route = &$routes[$i]; --$i) {
//            if (preg_match("~^(?|{$route[0]})~", "$uri$this->tail", $matches)) {
//                array_shift($matches);
//                return [$route[1][strlen(array_pop($matches))], &$matches];
//            }
//        }
        foreach ($routes as &$route) {
            if (preg_match("~^(?|{$route[0]})~", "$uri$this->tail", $matches)) {
                array_shift($matches);
//                var_dump($route); die;
                return [$route[1][strlen(array_pop($matches))], &$matches];
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
