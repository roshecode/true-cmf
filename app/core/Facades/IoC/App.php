<?php

namespace True\Facades\IoC;

use True\Facades\Facade;
use True\IoC\Container;

/**
 * Class App
 * @package True\Facades\IoC
 * @subpackage True\IoC\Container
 */
final class App extends Facade
{
    public static function init() {
        parent::setInstance(new Container());
    }
}

App::init();
