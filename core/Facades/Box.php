<?php
namespace T\Facades;

use T\Abstracts\Facade;

class Box extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\Box::class;
    }
}
