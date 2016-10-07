<?php

namespace T\Support\Facades;

use T\Support\Abstracts\Facade;

class View extends Facade
{
    protected static function getFacadeAccessor() {
        return 'View';
    }
}
