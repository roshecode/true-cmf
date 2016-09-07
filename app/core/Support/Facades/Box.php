<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;

class Box extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Box';
    }
}
