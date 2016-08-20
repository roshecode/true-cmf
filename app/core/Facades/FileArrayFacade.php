<?php

namespace Truth\Facades;

use Truth\Data\FileSystem\FS;

class FileArrayFacade extends Facade
{
    /**
     * @var \Truth\Data\FileSystem\FileArrayQuery
     */
    protected static $instance;

    public static function load($filePath) {
        parent::setInstance(FS::getAssoc(APP_DIR . $filePath));
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