<?php

namespace Truth\Support\Facades;

use InvalidArgumentException;
use Truth\Data\FileSystem\FileArrayQuery;
use Truth\Facades\FileArrayFacade;

class Lang extends FileArrayFacade
{
    /**
     * @var FileArrayQuery
     */
    protected static $instance;

    const BASE_LANG = 'en-EN';

    /**
     * @param string $filePath
     *
     * @throws InvalidArgumentException
     */
    public static function load($filePath) {
        parent::load($filePath);

//        if (Config::get('localization.language') === self::BASE_LANG) {
//
//        }
//        self::$data = File::inc($filePath);
//        if (self::$base_data === null) {
//            self::$base_data = File::reqOnce(Config::getDirectoryPath('languages').self::BASE_LANG.'.php');
//        }
    }
}
