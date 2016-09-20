<?php

namespace Truth\Support\Services\Routing;

use Truth\Support\Services\Repository\Repository;

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
    private $path_array;
    private $path_array_count;

    public function __construct($host = null)
    {
        $this->host = $host;
        $this->routes = new RouteRepository();
//        $this->routes->set('true/:user/profile/hello/:id', 'POST');
//        $this->routes->set('true/:page/:number/1one', 'GET');
//        $this->routes->set('true/page/:name', 'HEAD');
//        $this->routes->make('true/roman/profile/hello/2');
//        pr($this->routes->getData());
//        dd('end');
    }

    public function start() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
//        $this->host = $_SERVER['HTTP_HOST'];
        $this->path = $parse_uri['path'];
        $this->query = isset($parse_uri['query']) ? $parse_uri['query'] : null;
        $this->routes->make($this->host . $this->path);
    }

    public function match($methods, $uri, $handler) {
//        $host = $_SERVER['HTTP_HOST'];
        $this->routes->set($this->host . $uri, $handler);
    }

    public function redirect($url) {
        header('Location: ', $url);
    }

    public function __destruct()
    {
        $this->start();
        d($this);
    }
}
