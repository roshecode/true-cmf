<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;

class Config extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Config';
    }
}
