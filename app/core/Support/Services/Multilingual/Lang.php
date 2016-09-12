<?php

namespace Truth\Support\Services\Multilingual;

use Truth\Support\Services\FileSystem\FS;
use Truth\Support\Services\Repository\FileRepository;

class Lang extends FileRepository
{
    protected $baseLang = 'en-EN';

    /**
     * @param FS $fileSystem
     * @param string $filePath
     */
    public function __construct(FS &$fileSystem, $filePath) {
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
