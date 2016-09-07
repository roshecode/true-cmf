<?php

namespace Truth\Support\Services\Multilingual;

use InvalidArgumentException;
use Truth\Support\Abstracts\FileRepository;

class Lang extends FileRepository
{
    const BASE_LANG = 'en-EN';

    public static function register(&$box)
    {
        $box->singleton('Lang', self::CORE_SERVICES . '\\Multilingual\\Lang');
    }

    /**
     * @param \Truth\Support\Services\FileSystem\FS $fileSystem
     * @param string $filePath
     */
    public function __construct($fileSystem, $filePath) {
        parent::__construct($fileSystem, $filePath);

//        if (Config::get('localization.language') === self::BASE_LANG) {
//
//        }
//        self::$data = File::inc($filePath);
//        if (self::$base_data === null) {
//            self::$base_data = File::reqOnce(Config::getDirectoryPath('languages').self::BASE_LANG.'.php');
//        }
    }
}
