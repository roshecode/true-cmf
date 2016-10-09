<?php

namespace T\Facades;

use T\Abstracts\Facade;

class View extends Facade
{
    protected static function getFacadeAccessor() {
        return 'View';
    }
}
