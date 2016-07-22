<?php

namespace True\Routing;

class Route
{
    private static $host;
    private static $path;
    private static $query;

    public function __construct($host = null)
    {
        self::$host = $host;
    }

    public static function start() {
        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        self::$path = $parse_url['path'];
        if (isset($parse_url['query'])) self::$query = $parse_url['query'];
        else self::$query = null;
    }

    public static function get($url, $func) {
        if (is_string($func)) {
            // controller
        } elseif (is_callable($func)) {
            $func('Roman');
        }
    }
}
