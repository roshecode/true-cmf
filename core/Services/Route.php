<?php
namespace Core\Services;

use True\Standards\Container\BootableInterface;
use True\Support\Routing\RouteCollector;

class Route extends RouteCollector implements Contracts\Route, BootableInterface
{
    public function __boot() {
//        if (!($parse_uri = parse_url($_SERVER['REQUEST_URI']))) throw new \Exception('Invalid uri');
//        $path = &$parse_uri['path'];
//        $this->make($_SERVER['REQUEST_METHOD'], $path)[0]();
    }

    /**
     * @param string $route
     * @param string|array|\Closure $handler
     */
    public function any(string $route, $handler) {
        $this->match(['DELETE', 'GET', 'HEAD', 'OPTIONS', 'PATCH', 'POST', 'PUT'], $route, $handler);
    }

    /**
     * @param string|array|\Closure $handler
     */
    public function api($handler) {
        $this->prefix('/api', $handler);
    }
}
