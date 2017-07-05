<?php
namespace Truecode\Routing;

class RouteCollection
{
    const ROUTES_CHUNK_LIMIT = 96;
    const REGEX_DELIMITER = ':';
    const VARIABLE_DELIMITER = ':';
    const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    private $backtrackLimit; // TODO: add mechanism for regex length limiting

    protected $routes = [];
    protected $count  = 0;
    private $tail;

    public function __construct(&$backtrackLimit, &$tail) {
        $this->backtrackLimit = $backtrackLimit;
        $this->tail = $tail;
    }

    public function add($route, $handler) {
        $count = &$this->count;
        $rest  = $count % static::ROUTES_CHUNK_LIMIT;
        $regex = preg_replace_callback(static::ROUTES_SPLIT_REGEX, function ($matches) {
                $node = &$matches[0];
                if ($node[0] == static::VARIABLE_DELIMITER) {
                    $regexp_parts = explode(static::REGEX_DELIMITER, $node, 3);
                    return count($regexp_parts) > 2 ? '(' . $regexp_parts[2] . ')' : '([^/]+)';
                }
                return $node;
            }, $route) . "/( {{$rest}})";
        $route = &$this->routes[(++$count - $rest) / static::ROUTES_CHUNK_LIMIT];
        $rest
            ? $route[0] = "$regex|$route[0]"
//            ? $route[0] .= "|$regex"
            : $route = \SplFixedArray::fromArray([
                $regex,
                new \SplFixedArray(static::ROUTES_CHUNK_LIMIT)
            ], false);
        $route[1][$rest] = $handler;
    }

    public function get($uri) {
        $matches = [];
        for ($i = count($this->routes) - 1; $i >= 0, $route = &$this->routes[$i]; --$i) {
            if (preg_match("~^(?|{$route[0]})~", "$uri$this->tail", $matches)) {
                array_shift($matches);
                return [$route[1][strlen(array_pop($matches))], &$matches];
            }
        }
//        foreach ($this->routes as &$route) {
//            if (preg_match("~^(?|{$route[0]})~", "$uri$this->tail", $matches)) {
//                array_shift($matches);
//                return [$route[1][strlen(array_pop($matches))], &$matches];
//            }
//        }
        return [function($errorCode) {return "$errorCode Not found";}, [404]];
    }
}
