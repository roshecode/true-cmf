<?php
namespace Core\Services\Facades;

use True\Standards\Container\AbstractFacade as Facade;

/**
 * Class App
 *
 * @method static make(string $abstract, array $params = [])
 * @see \Core\Services\Contracts\App::make()
 */
class App extends Facade
{
    protected static function getFacadeAccessor() {
        return \Core\Services\Contracts\App::class;
    }

    public static function start() {
        return 'START';
    }
}
