<?php

namespace Truth\Support\Services\Configuration;

use InvalidArgumentException;
use Truth\Support\Abstracts\Repository;

class Config extends Repository
{
    public static function register() {
        self::$box->singleton('Config', self::NS . '\\Configuration\\Config');
        $params = ['/core/config.php'];
        self::$box->make('Config', $params);
    }
    /**
     * @param string $filePath
     */
    public function __construct($filePath) {
        parent::__construct($filePath);
        $this->setLanguage($this->get('localization.language'));
        $this->setErrors($this->get('errors'));
    }

    /**
     * @param string $path
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function getDirectoryPath($path) {
        if (is_string($path)) {
            // TODO: directory separator to windows
//            return str_replace('/', '\\', self::get('directories')[$path]).DIRECTORY_SEPARATOR; //WINDOWS
            return str_replace('/', '\\', $this->get('directories')[$path]);
        } else {
            throw new InvalidArgumentException('exceptions.invalid_argument'); // TODO: Envisage
        }
    }

    /**
     * @param string $lang
     *
     * @throws InvalidArgumentException
     */
    public function setLanguage($lang) {
        if (is_string($lang)) {
//            Lang::load(self::getDirectoryPath('languages') . '/' . $lang.'.php');
            self::$box->make('Lang', [$this->getDirectoryPath('languages') . '/' . $lang.'.php']);
        } else {
            throw new InvalidArgumentException('exceptions.invalid_argument'); // TODO: Envisage
        }
    }

    /**
     * @param array $params
     *
     * @throws InvalidArgumentException
     */
    public function setErrors($params) {
        if (is_array($params)) {
            if (isset($params['display'])) {
                ini_set('display_errors', $params['display']);
                ini_set('display_startup_errors', $params['display']);
            }
            if (isset($params['reporting'])) {
                error_reporting($params['reporting']);
            }
        } else {
            throw new InvalidArgumentException('exceptions.invalid_argument');  // TODO: Envisage
        }
    }

    public function getThemePath() {
        return $this->getDirectoryPath('themes') . '/' . $this->get('site.theme');
    }
}