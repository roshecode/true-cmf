<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;

class Lang extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Lang';
    }
}
