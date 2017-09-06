<?php

namespace Core\Services\Facades;

use True\Standards\Container\AbstractFacade as Facade;

class View extends Facade
{
    protected static function getFacadeAccessor() {
        return \Core\Services\Contracts\View::class;
    }
}
