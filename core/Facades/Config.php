<?php

namespace T\Facades;

use T\Abstracts\Facade;

class Config extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\ConfigInterface::class;
    }
}
