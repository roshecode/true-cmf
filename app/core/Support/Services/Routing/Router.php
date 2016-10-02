<?php

namespace Truth\Support\Services\Routing;

class Router
{
    const ROUTES_GROUP_COUNT = 100;
    const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    protected $routes = [];
    protected $count  = -1;
    protected $tail   = '/';

    public function __construct()
    {
        $this->tail .= str_repeat('-', self::ROUTES_GROUP_COUNT - 1);
    }

    protected function add($method, $route, $handler) {
        $rest = ++$this->count % self::ROUTES_GROUP_COUNT;
        $regex = '(?:' . preg_replace_callback(self::ROUTES_SPLIT_REGEX, function ($matches) {
                $node = &$matches[0];
                if ($node[0] == ':') {
                    $parts_regexp = explode(':', $node, 3);
                    return '(' . (isset($parts_regexp[2]) ? $parts_regexp[2] : '[^/]+') . ')';
                }
                return $node;
            }, $route) . ')/(-{' . $rest . '})';
        $route = &$this->routes[$method][($this->count - $rest) / self::ROUTES_GROUP_COUNT];
        $rest ? $route[0] = $regex . '|' . $route[0] :
            $route = \SplFixedArray::fromArray([$regex, new \SplFixedArray(self::ROUTES_GROUP_COUNT)]);
        $route[1][$rest] = $handler;
    }

    public function match($methods, $route, $handler) {
        if (is_array($methods))
            foreach ($methods as $method)
                $this->add(strtoupper($method), $route, $handler);
        else $this->add(strtoupper($methods), $route, $handler);
    }

    public function get    ($route, $handler) { $this->add('GET'    , $route, $handler); }
    public function post   ($route, $handler) { $this->add('POST'   , $route, $handler); }
    public function put    ($route, $handler) { $this->add('PUT'    , $route, $handler); }
    public function patch  ($route, $handler) { $this->add('PATCH'  , $route, $handler); }
    public function delete ($route, $handler) { $this->add('DELETE' , $route, $handler); }
    public function options($route, $handler) { $this->add('OPTIONS', $route, $handler); }

    public function make($method, $uri) {
//        print_r(serialize($this->routes)); die;
        $uri .= $this->tail;
        $matches = [];
        $routes = &$this->routes[strtoupper($method)];
        for ($i = count($routes) - 1; $i >= 0; --$i) {
            if (preg_match('~^(?|' . $routes[$i][0] . ')~i', $uri, $matches)) {
                unset($matches[0]);
                return [$routes[$i][1][strlen(array_pop($matches))], &$matches];
            }
        }
        return 404;
    }

    public function run() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
        $make = $parse_uri ? $this->make($_SERVER['REQUEST_METHOD'], $_SERVER['HTTP_HOST'] . $parse_uri['path']) : 404;
//        $make = $this->make('GET', 'true/stroka-sub/1235');
        call_user_func_array($make[0], $make[1]);
    }

    public function __destruct()
    {
        $this->run();
    }
}