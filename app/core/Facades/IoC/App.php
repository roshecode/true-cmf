<?php

namespace True\Facades\IoC;

use True\Facades\Facade;
use True\IoC\Container;

/**
 * Class App
 * @method static add
 * @method static has
 * @method static get
 * @package True\Facades\IoC
 */
final class App extends Facade
{
    /**
     * @var Container
     */
    protected static $instance;

    public static function init() {
        parent::setInstance(new Container());
    }
}
