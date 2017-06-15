<?php
namespace T\Services;

use T\Interfaces\Route as RouterInterface;
use T\Traits\Service;
use Truecode\Routing\RouteCollector;

class Route extends RouteCollector implements RouterInterface
{
    use Service;

    public function __boot() {
//        include __DIR__ . '/../../app/Routes/Api/api.php'; // todo: load all files (folders names as namespaces)
        include __DIR__ . '/../../app/Routes/index.php';
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
