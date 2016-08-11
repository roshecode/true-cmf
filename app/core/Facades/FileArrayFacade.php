<?php

namespace True\Facades;

use True\Data\FileSystem\File;

class FileArrayFacade extends Facade
{
    /**
     * @var \True\Data\FileSystem\FileArray
     */
    protected static $instance;

    public static function load($filePath) {
        parent::setInstance(File::loadArray(APP_DIR . $filePath));
//        static::$data = File::loadArray($filePath);
    }

    public static function get($query) {
        return static::$instance->get($query);
    }

    public static function set($query, $value) {
        static::$instance->set($query, $value);
    }

    // TODO: delete this function
    public static function getAll() {
        return static::$instance;
    }
}