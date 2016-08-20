<?php

namespace Truth\System;

use Truth\Exceptions\FileNotFoundException;
use Truth\Exceptions\FileUnreadableException;

class Loader {
//    private $prefix;
//    private $extension;

    public static function register($prefix, $base_dir/* = __DIR__*/, $extension = '.php') {
        spl_autoload_register(function($class) use($prefix, $base_dir, $extension) {

//            $prefix = $prefix . '\\';
            $base_dir = getcwd() . $base_dir . DIRECTORY_SEPARATOR;
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                // if the class doesn't use the namespace prefix move to the next registered autoloader
                return;
            }
            $relative_class = substr($class, $len);
            // TODO: Linux and Windows directory separator
            $filePath = $base_dir . (DIRECTORY_SEPARATOR == '/' ?
                    str_replace('\\', '/', $relative_class) : $relative_class) . $extension;
            if (file_exists($filePath)) {
                if (is_readable($filePath)) {
                    require_once $filePath;
                } else {
//                    throw new FileUnreadableException(Lang::get('exceptions.file_is_unreadable'));
                    throw new FileUnreadableException('AUTOLOADER: exceptions.file_is_unreadable');
                }
            } else {
                throw new FileNotFoundException(('AUTOLOADER: exceptions.file_not_found'));
            }
        });
    }
}
