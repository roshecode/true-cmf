<?php

namespace Truth\Support\Services\Routing;

class Router
{
    const POST      = 'POST';
    const GET       = 'GET';
    const DELETE    = 'DELETE';
    const PUT       = 'PUT';
    const PATCH     = 'PATCH';
    const OPTIONS   = 'OPTIONS';

    const CODE_403  = 403;
    const CODE_404  = 404;

    const FILTER_LETTERS = 'letters';
    const FILTER_INT     = 'int';
    const FILTER_UINT    = 'uint';

    const CONFIG_SEPARATOR     = 0;
    const CONFIG_PLACEHOLDER   = 1;
    const CONFIG_REGEX_WRAPPER = 2;

    protected $host;
    protected $path;
    protected $query;

    protected $routes;

    protected $separator;
    protected $placeHolder;
    protected $regexWrapper;
    /**
     * @var RouteNode|callable
     */
    protected $pointer;
    protected $params;

    protected static $filters = [
        self::FILTER_LETTERS    => '^[a-zA-Z]+$',
        self::FILTER_INT        => '^-?\d+$',
        self::FILTER_UINT       => '^\d+$',
    ];

    public function __construct($host = null, $data = null, $config = [
        self::CONFIG_SEPARATOR => '\/', self::CONFIG_PLACEHOLDER => ':', self::CONFIG_REGEX_WRAPPER => '/'])
    {
        $this->host = $host;
        $this->routes = $data ? $data : new RouteNode();
        $this->separator    = $config[self::CONFIG_SEPARATOR];
        $this->placeHolder  = $config[self::CONFIG_PLACEHOLDER];
        $this->regexWrapper = $config[self::CONFIG_REGEX_WRAPPER];
    }

    public function start() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
        if ($parse_uri) {
            $this->path = $parse_uri['path'];
            $this->query = isset($parse_uri['query']) ? $parse_uri['query'] : null;
            $this->make($_SERVER['HTTP_HOST'] . $this->path);
        } else throw new \Exception('404');
    }

    protected function pass($uri, $callback) {
        $this->pointer = &$this->routes;
        preg_replace_callback(
            $this->regexWrapper        . $this->placeHolder . '?[^' .
            $this->separator . ']+(?!' . $this->separator   . ')?'  .
            $this->regexWrapper . 'i', $callback, $uri);
    }

    public function match($methods, $uri, $handler) {
        $this->pass($this->host . $uri, function($matches) {
            $this->pointer = &$this->pointer->add($matches[0], $this->regexWrapper);
        });
        $this->pointer->setMake($handler);
    }

    public function make($uri) {
        $this->params = []; // reset params
        $this->pass($uri, function($matches) {
            $this->pointer = $this->pointer->match($matches[0], $this->params);
        });
        $this->pointer->make($this->params);
    }

    public static function getFilter($name) {
        return self::$filters[$name];
    }

    public function redirect($url) {
        header('Location: ', $url);
    }

    public function __destruct()
    {
//        $_SERVER['HTTP_HOST'] = 'true';
//        $_SERVER['REQUEST_URI'] = '/';
        $this->start();
    }
}
