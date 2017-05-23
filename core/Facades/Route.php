<?php

namespace T\Facades;

use T\Abstracts\Facade;

/**
 * Class Route
 * @package T\Facades
 *
 * @method static get(string $route, string|\Closure $handler)
 * @see \Truecode\Routing\Route::get()
 */
class Route extends Facade
{
    protected static function getFacadeAccessor() {
        return \T\Interfaces\Route::class;
    }
}
