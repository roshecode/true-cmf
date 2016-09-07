<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;

class View extends Facade
{
    protected static function getFacadeAccessor() {
        return 'View';
    }
}
