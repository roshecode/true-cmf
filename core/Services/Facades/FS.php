<?php

namespace Core\Services\Facades;

use True\Standards\Container\AbstractFacade as Facade;

class FS extends Facade
{
    protected static function getFacadeAccessor() {
        return \Core\Services\Contracts\FS::class;
    }
}
