<?php

namespace T\Facades;

use T\Abstracts\Facade;

class FS extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\FSInterface::class;
    }
}
