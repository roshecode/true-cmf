<?php

namespace T\Support\Services\Http;

use T\Support\Abstracts\ServiceProvider;
use T\Support\Interfaces\RequestInterface;

class Request extends ServiceProvider implements RequestInterface
{
    protected $method;
    protected $domain;
    protected $path;

    public function __construct($domain, $method, $uri) {
        $this->method = $method;
        $this->domain = $domain;
        if (! ($parse_uri = parse_url($uri))) throw new \Exception('Invalid uri');
//        $this->path = ltrim($parse_uri['path'], '/');
        $this->path = &$parse_uri['path'];
    }

    public function method() {
        return $this->method;
    }

    public function domain() {
        return $this->domain;
    }

    public function path() {
        return $this->path;
    }

    public function fixMethod() {
        if (isset($_POST['_method'])) {
            $_SERVER['REQUEST_METHOD'] = $this->method = $_POST['_method'];
        }
        return $this;
    }

    public function __destruct() {
        $make = $this->box->make('Route')->makeFromRequest($this->box->make('Request')->fixMethod());
        call_user_func_array($make[0], $make[1]);
    }
}
