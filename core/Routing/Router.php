<?php

namespace True\Routing;


class Router
{
    protected static $path;
    protected static $query;

    public static function start() {
        if (substr(static::$path, -1) === '/') {
            header('Location: '.preg_replace('/(\/*)$/', '', $_SERVER['REDIRECT_URL']));
        }
        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        static::$path = $parse_url['path'];
        static::$query = $parse_url['query'];
        echo strrpos(static::$path, '/');
    }
}