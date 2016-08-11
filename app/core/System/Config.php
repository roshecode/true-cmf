<?php

namespace True\System;

use InvalidArgumentException;
use True\Data\FileSystem\FileArray;
use True\Data\FileSystem\FileArrayFacade;
use True\Exceptions\FileNotFoundException;
use True\Exceptions\FileUnreadableException;
use True\Multilingual\Lang;

class Config extends FileArrayFacade
{
    /**
     * @var FileArray
     */
    protected static $data;

    /**
     * @param string $filePath
     */
    public static function load($filePath) {
        parent::load($filePath);
        self::set('app_dir', getcwd());

        self::setLanguage(self::get('localization.language'));
        self::setErrors(self::get('errors'));
    }

    /**
     * @param string $path
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function getDirectoryPath($path) {
        if (is_string($path)) {
            // TODO: directory separator to windows
//            return str_replace('/', '\\', self::get('directories')[$path]).DIRECTORY_SEPARATOR; //WINDOWS
            return str_replace('/', '\\', self::get('directories')[$path]);
        } else {
            throw new InvalidArgumentException(Lang::get('exceptions.invalid_argument'));
        }
    }

    /**
     * @param string $lang
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public static function setLanguage($lang) {
        if (is_string($lang)) {
            Lang::load(self::getDirectoryPath('languages') . '/' . $lang.'.php');
        } else {
            throw new InvalidArgumentException(Lang::get('exceptions.invalid_argument'));
        }
    }

    /**
     * @param array $params
     *
     * @throws InvalidArgumentException
     */
    public static function setErrors($params) {
        if (is_array($params)) {
            if (isset($params['display'])) {
                ini_set('display_errors', $params['display']);
                ini_set('display_startup_errors', $params['display']);
            }
            if (isset($params['reporting'])) {
                error_reporting($params['reporting']);
            }
        } else {
            throw new InvalidArgumentException(Lang::get('exceptions')['invalid_argument']);
        }
    }

    public static function getThemePath() {
        return self::getDirectoryPath('themes') . '/' . self::get('site.theme');
    }
}