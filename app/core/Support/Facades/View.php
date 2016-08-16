<?php

namespace True\Support\Facades;

use True\Facades\Facade;
use True\Support\Interfaces\ViewInterface;

final class View extends Facade// implements FacadeInterface
{
    public static $class = __CLASS__;
    protected static $name;

    public static function register(ViewInterface $service) {
//        if (class_exists())
        // new TemplateParser($service)

        App::add(static::$name = 'View', $service);
//        self::$instance = App::get('View');
//        parent::setInstance(App::get('View'));
    }
}