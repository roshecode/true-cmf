<?php

namespace T\Support\Facades;

use T\Support\Abstracts\Facade;

class Box extends Facade
{
    protected static function getFacadeAccessor() {
        return 'Box';
    }
}
