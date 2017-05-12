<?php

namespace T\Facades;

use T\Abstracts\Facade;

class Lang extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\Lang::class;
    }
}
