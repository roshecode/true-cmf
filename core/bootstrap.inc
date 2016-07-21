<?php

require_once __DIR__ . '/config.php';

class Autoloader {
//    private $prefix;
//    private $extension;

    public static function register($prefix, $base_dir = __DIR__ . '\\', $extension = '.php') {
//        $this->prefix = $prefix;
//        spl_autoload_register(array($this, '_loadClass'));
        spl_autoload_register(function($class) use($prefix, $base_dir, $extension) {
            $prefix = $prefix . '\\';
            $base_dir = __DIR__ . DIRECTORY_SEPARATOR;
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                // if the class doesn't use the namespace prefix move to the next registered autoloader
                return;
            }
            $relative_class = substr($class, $len);
            $file = $base_dir . (DIRECTORY_SEPARATOR == '/' ? str_replace('\\', '/', $relative_class) : $relative_class) . $extension;
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }
}

//spl_autoload_register(function ($class)
//{
//    $prefix = 'True\\';
//    $base_dir = __DIR__ . '/';
//    $len = strlen($prefix);
//    if (strncmp($prefix, $class, $len) !== 0) {
//        // if the class doesn't use the namespace prefix move to the next registered autoloader
//        return;
//    }
//    $relative_class = substr($class, $len);
//    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.class.php';
//    if (file_exists($file)) {
//        require_once $file;
//    }
//});
