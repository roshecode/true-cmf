<?php

namespace True\Data\FileSystem;

class FileArrayFacade
{
    /**
     * @var FileArray
     */
    protected static $data;

    public static function load($filePath) {
        static::$data = File::loadArray($filePath);
    }

    public static function get($query) {
        return static::$data->get($query);
    }

    public static function set($query, $value) {
        static::$data->set($query, $value);
    }

    // TODO: delete this function
    public static function getAll() {
        return static::$data;
    }
}