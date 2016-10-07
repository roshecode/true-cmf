<?php

namespace T\Support\Facades;

use T\Support\Abstracts\Facade;

class Lang extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Lang';
    }
}
