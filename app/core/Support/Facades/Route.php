<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;

class Route extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Route';
    }
}
