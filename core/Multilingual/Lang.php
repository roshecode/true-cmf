<?php

namespace True\Multilingual;

use InvalidArgumentException;
use True\Data\FileSystem\File;
use True\Data\FileSystem\FileArray;
use True\System\Config;

class Lang extends FileArray
{
    const BASE_LANG = 'en';

    protected static $base_data = null;

    /**
     * @param string $filePath
     *
     * @throws InvalidArgumentException
     */
    public static function load($filePath) {
        if (Config::get('localization.language') === 'en')
        self::$data = File::inc($filePath);
        if (self::$base_data === null) {
            self::$base_data = File::reqOnce(Config::getPath('languages').self::BASE_LANG.'.php');
        }
    }

    /**
     * @param string $param
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public static function get($param) {
        $getParam = parent::get($param);
        if ($getParam === null) {

        }
        if (array_key_exists($param, self::$data)) {
            return self::$data[$param];
        } else {
            return self::$base_data[$param];
        }
    }
}
