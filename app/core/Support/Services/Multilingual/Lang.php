<?php

namespace T\Support\Services\Multilingual;

use T\Support\Interfaces\LanguageInterface;
use T\Support\Services\FileSystem\FS;
use T\Support\Services\Repository\MultiFileRepository;

class Lang extends MultiFileRepository implements LanguageInterface
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

    public function parse($str, array $data, $wrapLeft = '', $wrapRight = '') {
        return preg_replace_callback('|{{\s*\w+\s*}}|U', function($matches) use($data, $wrapLeft, $wrapRight) {
            $key = trim($matches[0], '{ }');
            return isset($data[$key]) ? $wrapLeft . $data[$key] . $wrapRight : '';
        }, $str);
    }

    public function exception($exception, array $data) {
        $debug = &$this->data['debug'];
        $exceptions = &$debug['exceptions'];
        return $this->parse(
            $debug['before'] . $exceptions[$exception] . $debug['after'], $data, '<strong>', '</strong>');
    }

    public function notice($notice, array $data) {
        $debug = &$this->data['debug'];
        $notices = &$debug['notices'];
        return $this->parse(
            $debug['before'] . $notices[$notice] . $debug['after'], $data, '<strong>', '</strong>');
    }
}
