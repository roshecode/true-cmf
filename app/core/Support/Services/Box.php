<?php

namespace Truth\Support\Services;

use Truth\Support\Abstracts\ServiceProvider;

class Box extends ServiceProvider
{
    public static function register() {
        self::$box->instance('Box', self::$box);
    }
}