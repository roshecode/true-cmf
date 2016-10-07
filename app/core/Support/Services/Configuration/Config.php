<?php

namespace T\Support\Services\Configuration;

use InvalidArgumentException;
use T\Support\Interfaces\ConfigurationInterface;
use T\Support\Services\FileSystem\FS;
use T\Support\Services\Repository\MultiFileRepository;

class Config extends MultiFileRepository implements ConfigurationInterface
{
    /**
     * @param FS $fileSystem
     * @param string $filePaths
     */
    public function __construct(FS &$fileSystem, $filePaths) {
        parent::__construct($fileSystem, $filePaths);
    }

    public function boot() {
//        $this->setLanguage($this->data['main']['localization']['language']);
        $this->setErrors($this->data['main']['errors']);
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
            return $this->data['main']['directories'][$path] . '/';
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
            $this->box->make('Lang', [$this->box->make('Lang')->getBasedir(), $lang . '.php']); // TODO: Bad make
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

    public function getCurrentThemeName() {
        return $this->data['main']['site']['theme'];
    }

    public function getCurrentThemePath() {
        return $this->getDirectoryPath('themes') . '/' . $this->getCurrentThemeName();
    }
}
