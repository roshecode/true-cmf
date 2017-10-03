<?php
namespace Core\Services\Facades;

use True\Standards\Container\{AbstractFacade as Facade, ContainerInterface};

/**
 * Class App
 *
 * @method static make(string $abstract, array $params = [])
 * @see \Core\Services\Contracts\App::make()
 */
class App extends Facade
{
    protected static function getFacadeAccessor() {
        return ContainerInterface::class;
    }
}
