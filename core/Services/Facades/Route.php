<?php

namespace Core\Services\Facades;

use True\Standards\Container\AbstractFacade as Facade;

/**
 * Class Route
 * @package T\Facades
 *
 * @method static get(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::get
 * @method static post(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::post
 * @method static put(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::put
 * @method static patch(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::patch
 * @method static delete(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::delete
 * @method static options(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::options
 * @method static match(string|array $methods, string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::match
 * @method static make(string $method, string $route)
 * @see \Core\Services\Contracts\Route::make
 * @method static prefix(string $prefix, string|array|\Closure $group)
 * @see \Core\Services\Contracts\Route::prefix
 * @method static any(string $route, string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::any
 * @method static api(string|array|\Closure $handler)
 * @see \Core\Services\Contracts\Route::api
 */
class Route extends Facade
{
    /**
     * @return string Facade accessor
     */
    protected static function getFacadeAccessor() {
        return \Core\Services\Contracts\Route::class;
    }
}
