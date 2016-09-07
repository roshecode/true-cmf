<?php

namespace Truth\Support\Abstracts;

abstract class ServiceProvider extends Entity
{
    const NS = "Truth\\Support\\Services";
    /**
     * @return void
     */
    public static function register() {
        self::$box->bind(get_called_class());
    }
}