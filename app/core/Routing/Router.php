<?php

namespace True\Routing;

use True\Multilingual\Lang;

class Router
{
    private static $host;
    private static $path;
    private static $query;
    /**
     * @var RouteNode
     */
    private static $route;
    private static $pointer;
    private static $path_array;
    private static $path_array_count;

    public function __construct($host = null)
    {
        if (is_string($host)) {
            self::$host = $host;
        } else {
            throw new \InvalidArgumentException(Lang::get('exceptions')['invalidArgument']['host']);
        }
    }

    public static function start() {
        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        self::$path = $parse_url['path'];

        self::$path_array = explode('/', self::$path);
        array_shift(self::$path_array);
//        dd(self::$path_array);
        self::$path_array_count = count(self::$path_array);

        if (isset($parse_url['query'])) self::$query = $parse_url['query'];
        else self::$query = null;
    }

    private static function setNodes($parent, $method) {
        static $iterator = 1;
        echo $iterator.':'.self::$path_array_count;
        if ($iterator !== self::$path_array_count) {
            self::$pointer = new RouteNode(self::$path_array[$iterator], $parent);
            $parent->addChild(self::$pointer);
            ++$iterator;
            self::setNodes(self::$pointer, $method);
        }
        $iterator = 1;
//        return $method;
        self::$pointer->setMethod($method);
    }

    public static function get($url, $func) {
//        $parse_url = parse_url($url);
//        self::$path_array = explode('/', $parse_url['path']);
//        self::$path_array_count = count(self::$path_array);
        self::$pointer = self::$route = new RouteNode(self::$path_array[0]);
        self::setNodes(self::$route, 'GET');

        dd(self::$route);

//        if (is_string($func)) {
//            // controller
//        } elseif (is_callable($func)) {
//            $func('Roman');
//        }
    }

    public static function redirect($url) {
        header('Location: ', $url);
    }
}
