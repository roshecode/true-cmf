<?php

namespace True\System;

use True\Exceptions\FileNotFoundException;
use True\Exceptions\FileUnreadableException;

class Loader {
//    private $prefix;
//    private $extension;

    public static function register($prefix, $base_dir/* = __DIR__*/, $extension = '.php') {
        spl_autoload_register(function($class) use($prefix, $base_dir, $extension) {
            $prefix = $prefix . '\\';
            $base_dir = getcwd() . $base_dir . DIRECTORY_SEPARATOR;
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                // if the class doesn't use the namespace prefix move to the next registered autoloader
                return;
            }
            $relative_class = substr($class, $len);
            $filePath = $base_dir . (DIRECTORY_SEPARATOR == '/' ?
                    str_replace('\\', '/', $relative_class) : $relative_class) . $extension;
            if (file_exists($filePath)) {
                if (is_readable($filePath)) {
                    require_once $filePath;
                } else {
                    throw new FileUnreadableException('File is unreadable');
                }
            } else {
                throw new FileNotFoundException('File not found'/*Lang::get('FileNotFound')*/);
            }
        });
    }
}
