<?php

namespace T\Support\Facades;

use T\Support\Abstracts\Facade;

class Route extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Route';
    }
}
