<?php

namespace True\System;

use InvalidArgumentException;
use True\Data\FileSystem\FileArray;
use True\Exceptions\FileNotFoundException;
use True\Exceptions\FileUnreadableException;
use True\Multilingual\Lang;

class Config extends FileArray
{
    /**
     * @param string $filePath
     */
    public static function load($filePath) {
        parent::load($filePath);
        self::setLanguage(self::$data['localization']['language']);
        self::setErrors(self::$data['errors']);
        self::$data['app_dir'] = getcwd();
    }

    /**
     * @param string $path
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function getPath($path) {
        if (is_string($path)) {
            return Config::get('directories')[$path].DIRECTORY_SEPARATOR;
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
            Lang::load(self::$data['directories']['languages'].DIRECTORY_SEPARATOR.$lang.'.php');
        } else {
            throw new InvalidArgumentException(Lang::get('exceptions')['invalid_argument']);
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
}