<?php
namespace Core\Services;

use True\Support\Container\Container;
use True\Standards\Container\BootableInterface;

class App extends Container implements Contracts\App, BootableInterface
{
    const DEFAULT     = 'default';
    const DEVELOPMENT = 'development';
    const PRODUCTION  = 'production';
    const MAINTENANCE = 'maintenance';

    public function __construct($config = null)
    {
        // set errors handler
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//        $whoops->register();

        // init dotenv
        (new \Dotenv\Dotenv(BASEDIR))->load();

        $this->instance(Contracts\App::class, $this);

        parent::__construct($config);
    }

    /**
     * Executes after construct
     */
    public function __boot()
    {
    }
}
