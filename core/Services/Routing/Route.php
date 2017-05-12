<?php
namespace T\Services\Routing;

use T\Interfaces\Route as RouterInterface;
use T\Traits\Service;

class Route implements RouterInterface
{
    use Service;

    const DELETE    = 'DELETE';
    const GET       = 'GET';
    const HEAD      = 'HEAD';
    const PATCH     = 'PATCH';
    const POST      = 'POST';
    const PUT       = 'PUT';

    const ROUTES_CHUNK_LIMIT = 100;
    const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    protected $domain;
    protected $routes = [];
    protected $count  = -1;
    protected $tail   = '/';
    
    public function __construct($domain = '') {
        $this->domain = $domain;
        $this->tail .= str_repeat(' ', self::ROUTES_CHUNK_LIMIT - 1);
    }

    public function boot()
    {
        include __DIR__ . '/../../../app/Routes/Api/api.php'; // todo: load all files (folders names as namespaces)
//        if (!($parse_uri = parse_url($_SERVER['REQUEST_URI']))) throw new \Exception('Invalid uri');
//        $path = &$parse_uri['path'];
//        $this->make($_SERVER['REQUEST_METHOD'], $path)[0]();
    }

    public function get($route, $handler) {
        $this->add('GET', $route, $handler);
    }
    
    public function post($route, $handler) {
        $this->add('POST', $route, $handler);
    }
    
    public function put($route, $handler) {
        $this->add('PUT', $route, $handler);
    }
    
    public function patch($route, $handler) {
        $this->add('PATCH', $route, $handler);
    }
    
    public function delete($route, $handler) {
        $this->add('DELETE', $route, $handler);
    }
    
    public function options($route, $handler) {
        $this->add('OPTIONS', $route, $handler);
    }
    
    protected function add($method, $route, $handler) {
        $route = $route[0] == '/' ? $route : '/' . $route;
        $rest  = ++$this->count % self::ROUTES_CHUNK_LIMIT;
        $regex = '(?:' . preg_replace_callback(self::ROUTES_SPLIT_REGEX, function ($matches) {
                $node = &$matches[0];
                if ($node[0] == ':') {
                    $parts_regexp = explode(':', $node, 3);
                    return '(' . (isset($parts_regexp[2]) ? $parts_regexp[2] : '[^/]+') . ')';
                }
                return $node;
            }, $route) . ')/( {' . ($rest + 1) . '})';
        $route = &$this->routes[$this->domain][$method][($this->count - $rest) / self::ROUTES_CHUNK_LIMIT];
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
    
    public function domain($domain) {
        $this->domain = $domain;
        return $this;
    }
    
    public function make($method, $uri) {
        $uri .= $this->tail;
        $matches = [];
        $routes  = &$this->routes[$this->domain][strtoupper($method)];
        for ($i = count($routes) - 1; $i >= 0; --$i) {
            if (preg_match('~^(?|' . $routes[$i][0] . ')~i', $uri, $matches)) {
                unset($matches[0]);
                return [$routes[$i][1][strlen(array_pop($matches)) - 1], &$matches];
            }
        }
        return [function() {echo 'Not found';}, [404]];
    }
}
