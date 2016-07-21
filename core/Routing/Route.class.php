<?php

namespace TF\Routing;

class Route
{
    private static $path;
    private static $query;

    protected static function parseUrl($url) {

    }

    public static function start() {
        $parse_url_arr = parse_url($_SERVER['REQUEST_URI']);
        self::$path = $parse_url_arr['path'];
        self::$query = $parse_url_arr['query'];
    }

    public static function get($url, $func) {
        if (is_string($func)) {
            // controller
        } elseif (is_callable($func)) {
            $func('Roman');
        }
    }
}

class RouteNode
{
//    protected
}
