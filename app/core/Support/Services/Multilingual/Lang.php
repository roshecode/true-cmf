<?php

namespace Truth\Support\Services\Multilingual;

use InvalidArgumentException;
use Truth\Support\Abstracts\Repository;

class Lang extends Repository
{
    const BASE_LANG = 'en-EN';

    public static function register()
    {
        self::$box->singleton('Lang', self::NS . '\\Multilingual\\Lang');
    }

    /**
     * @param string $filePath
     *
     * @throws InvalidArgumentException
     */
    public function __construct($filePath) {
        parent::__construct($filePath);

//        if (Config::get('localization.language') === self::BASE_LANG) {
//
//        }
//        self::$data = File::inc($filePath);
//        if (self::$base_data === null) {
//            self::$base_data = File::reqOnce(Config::getDirectoryPath('languages').self::BASE_LANG.'.php');
//        }
    }
}
