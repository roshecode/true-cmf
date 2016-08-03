<?php

namespace True\Multilingual;

use InvalidArgumentException;
use True\Data\FileSystem\File;
use True\Data\FileSystem\FileArray;
use True\Data\FileSystem\FileArrayFacade;
use True\System\Config;

class Lang extends FileArrayFacade
{
    const BASE_LANG = 'en-EN';

    /**
     * @var FileArray
     */
    protected static $data;

    protected static $base_data = null;

    /**
     * @param string $filePath
     *
     * @throws InvalidArgumentException
     */
    public static function load($filePath) {
        parent::load(Config::get('app_dir').$filePath);

//        if (Config::get('localization.language') === self::BASE_LANG) {
//
//        }
//        self::$data = File::inc($filePath);
//        if (self::$base_data === null) {
//            self::$base_data = File::reqOnce(Config::getDirectoryPath('languages').self::BASE_LANG.'.php');
//        }
    }
}
