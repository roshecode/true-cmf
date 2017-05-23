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

    private const REGEX_KEY = 0;
    private const MAP_KEY = 1;
    private const HANDLERS_KEY = 2;

    protected const REGEX_DELIMITER = ':';
    protected const VARIABLE_DELIMITER = ':';
    protected const ROUTES_CHUNK_LIMIT = 128;
    protected const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    private $backtrackLimit;

    protected $routes = [];
    protected $count  = -1;
    protected $tail;

    public function __construct() {
        $this->backtrackLimit = ini_get('pcre.backtrack_limit');
        $this->tail = str_repeat('/', static::ROUTES_CHUNK_LIMIT);
    }
    
    protected function add($method, $route, $handler) {
//        $hash = crc32($route);
        $map = $route;
        $route = $route[0] == '/' ? $route : '/' . $route;
        $rest  = ++$this->count % static::ROUTES_CHUNK_LIMIT;
        $regex = preg_replace_callback(static::ROUTES_SPLIT_REGEX, function ($matches) {
                $node = &$matches[0];
                if ($node[0] == static::VARIABLE_DELIMITER) {
                    $regexp_parts = explode(static::REGEX_DELIMITER, $node, 3);
                    return count($regexp_parts) > 2 ? '(' . $regexp_parts[2] . ')' : '([^/]+)';
                }
                return $node;
            }, $route) . "(/{{$rest}})";
        $route = &$this->routes[$method][($this->count - $rest) / static::ROUTES_CHUNK_LIMIT];
//        $rest
////            ? $route[0] = $regex . "/( {{$rest}})" . '|' . $route[0]
//            ? $route[self::REGEX_KEY] .= "|$regex"
//            : $route = \SplFixedArray::fromArray([$regex, new \SplFixedArray(static::ROUTES_CHUNK_LIMIT)]);

        if ($rest) {
            $route[self::REGEX_KEY] .= "|$regex";
            $route[self::MAP_KEY] .= "|$rest-" . strlen($map);
        } else {
            $route = \SplFixedArray::fromArray([$regex,
                '0-' . strlen($map), new \SplFixedArray(static::ROUTES_CHUNK_LIMIT)]);
        }

        $route[self::HANDLERS_KEY][$rest] = $handler;
    }
    
    public function match($methods, $route, $handler) {
        foreach ((array) $methods as $method) {
            $this->add($method, $route, $handler);
        }
    }
    
    public function make($method, $uri) {
//        $uri .= $this->tail;
        $matches = [];
        $chunks  = &$this->routes[$method];
//        for ($i = count($chunks) - 1; $i >= 0; --$i) {
//        $chunksCount = count($chunks);
//        for ($i = 0; $i < $chunksCount; ++$i) {
//            if (preg_match("~^(?|{$chunks[$i][self::REGEX_KEY]})~", $uri . $this->tail, $matches)) {
                ////unset($matches[0]);
//                array_shift($matches);
//                return [$chunks[$i][self::HANDLERS_KEY][strlen(array_pop($matches))], &$matches];
//            }
//        }
        foreach ($chunks as &$chunk) {
            if (preg_match("~^(?|{$chunk[self::REGEX_KEY]})~", $uri . $this->tail, $matches)) {
//                unset($matches[0]);
                array_shift($matches);
                return [$chunk[self::HANDLERS_KEY][strlen(array_pop($matches))], &$matches];
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
