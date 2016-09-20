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
        $this->routes->set('true/:user/profile/hello/:id', 'POST');
        $this->routes->set('true/:page/:number/1one', 'GET');
        $this->routes->set('true/page/:name', 'HEAD');
        pr($this->routes->getData());
        dd('end');
    }

    public function start() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
        $this->host = $_SERVER['HTTP_HOST'];
        $this->path = $parse_uri['path'];
        $this->query = isset($parse_uri['query']) ? $parse_uri['query'] : null;

//        $this->path_array = explode('/', $this->path);
//        $this->path_array[0] = $this->host;
//        $this->path_array_count = count($this->path_array);

        $invoke = $this->routes->get($this->host . $this->path);
        if (is_callable($invoke)) {
//            $invoke('Roman');
            call_user_func_array($invoke, ['Romeo']);
        } else {
            echo '404';
        }
    }
    public function match($methods, $uri, $handler) {
        $host = $_SERVER['HTTP_HOST'];
        $this->routes->set($host . $uri, $handler);
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
