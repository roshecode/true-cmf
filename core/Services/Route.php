<?php
namespace T\Services;

use T\Interfaces\Route as RouterInterface;
use T\Traits\Service;

class Route extends \Truecode\Routing\Route implements RouterInterface
{
    use Service;

    public function __boot() {
        include __DIR__ . '/../../app/Routes/Api/api.php'; // todo: load all files (folders names as namespaces)
//        if (!($parse_uri = parse_url($_SERVER['REQUEST_URI']))) throw new \Exception('Invalid uri');
//        $path = &$parse_uri['path'];
//        $this->make($_SERVER['REQUEST_METHOD'], $path)[0]();
    }
}
