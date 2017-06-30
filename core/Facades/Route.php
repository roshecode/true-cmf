<?php

namespace T\Facades;

use T\Abstracts\Facade;

/**
 * Class Route
 * @package T\Facades
 *
 * @method static get(string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::get
 * @method static post(string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::post
 * @method static put(string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::put
 * @method static patch(string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::patch
 * @method static delete(string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::delete
 * @method static options(string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::options
 * @method static match(string|array $methods, string $route, string|array|\Closure $handler)
 * @see \T\Interfaces\Route::match
 * @method static make(string $method, string $route)
 * @see \T\Interfaces\Route::make
 * @method static prefix(string $prefix, string|array|\Closure $group)
 * @see \T\Interfaces\Route::prefix
 * @method static any(string $route, string|array|\Closure $handler)
 * @see \T\Services\Route::any
 * @method static api(string|array|\Closure $handler)
 * @see \T\Services\Route::api
 */
class Route extends Facade
{
    /**
     * @return string Facade accessor
     */
    protected static function getFacadeAccessor() {
        return \T\Interfaces\RouteInterface::class;
    }
}
