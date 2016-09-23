<?php

namespace Truth\Support\Services\Routing\ArrayRouter;

class Router
{
    const POST      = 'POST';
    const GET       = 'GET';
    const DELETE    = 'DELETE';
    const PUT       = 'PUT';
    const PATCH     = 'PATCH';
    const OPTIONS   = 'OPTIONS';

    private $host;
    private $path;
    private $query;

    private $routes;

    public function __construct($host = null)
    {
        $this->host = $host;
        $this->routes = new RouteRepository();
    }

    public function start() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
        if ($parse_uri) {
            $this->path = $parse_uri['path'];
            $this->query = isset($parse_uri['query']) ? $parse_uri['query'] : null;
            $this->routes->make($_SERVER['HTTP_HOST'] . $this->path);
        } else throw new \Exception('404');
    }

    public function match($methods, $uri, $handler) {
//        $host = $_SERVER['HTTP_HOST'];
        $this->routes->match($methods, $this->host . $uri, $handler);
    }

    public function redirect($url) {
        header('Location: ', $url);
    }

    public function __destruct()
    {
        $this->start();
//        pr($this);
    }
}
