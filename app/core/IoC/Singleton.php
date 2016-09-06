<?php

namespace Truth\IoC;


class Singleton
{
    private static $instance;

    final private function __construct() {
        self::$instance = 'good';
        return self::$instance;
    }
    final private function __clone() {}
    public function getInstance() {
        return isset(self::$instance) ? self::$instance : new Singleton;
    }
}