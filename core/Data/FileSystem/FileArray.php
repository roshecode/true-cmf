<?php

namespace True\Data\FileSystem;

use True\Data\RamSystem\ArrayQuery;
use True\Multilingual\Lang;

class FileArray
{
    /**
     * Loaded array from file.
     *
     * @var array
     */
    protected static $data;

    /**
     * Load array from file.
     *
     * @param string $filePath
     */
    public static function load($filePath) {
        self::$data = File::reqOnce($filePath);
    }

    /**
     * Get array value by query
     *
     * @param string $query
     * @return mixed
     */
    public static function get($query) {
        $query = new ArrayQuery($query);
        return $query->apply(self::$data);
    }
}