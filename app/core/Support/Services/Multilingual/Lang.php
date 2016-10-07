<?php

namespace T\Support\Services\Multilingual;

use T\Support\Interfaces\LanguageInterface;
use T\Support\Services\FileSystem\FS;
use T\Support\Services\Repository\FileRepository;

class Lang extends FileRepository implements LanguageInterface
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
