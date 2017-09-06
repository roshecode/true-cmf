<?php

namespace Core\Services\Facades;

use True\Standards\Container\AbstractFacade as Facade;

class Lang extends Facade
{
    protected static function getFacadeAccessor() {
        return \Core\Services\Contracts\Lang::class;
    }
}
