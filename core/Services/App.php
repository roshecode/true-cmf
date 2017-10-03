<?php
namespace Core\Services;

use True\Support\Container\Container;
use True\Standards\Container\BootableInterface;

class App extends Container implements BootableInterface
{
    /**
     * Executes after construct
     */
    public function __boot()
    {
        // set errors handler
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//        $whoops->register();
    }

    //TODO
    public function boot(\Whoops\Run $whoops, \Whoops\Handler\PrettyPageHandler $handler)
    {
        $whoops->pushHandler($handler);
        $whoops->register();
    }
}
