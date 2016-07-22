<?php

namespace System;

class Config
{
    private static $settings;

    public static function load($file) {
        if (file_exists($file)) {
            if (is_readable($file)) {
                self::$settings = require_once $file;
            }
        }
        self::$settings['app_dir'] = getcwd();
    }
}