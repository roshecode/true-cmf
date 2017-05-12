<?php

namespace T\Facades;

use T\Abstracts\Facade;

class Route extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\Route::class;
    }
}
