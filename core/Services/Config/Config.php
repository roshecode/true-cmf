<?php
namespace T\Services\Config;

use T\Services\FileSystem\FS;
use T\Services\ArrayObject\MultiFileArrayObject;
use T\Interfaces\Config as ConfigInterface;

class Config extends MultiFileArrayObject implements ConfigInterface
{
    protected $inferiors;
    
    /**
     * @param FS     $fileSystem
     * @param string $filePaths
     * @param string $separator
     */
    public function __construct(FS &$fileSystem, $filePaths, $separator = '.') {
        parent::__construct($fileSystem, $filePaths, $separator);
    }
    
    public function boot() {
        $this->inferiors['Lang'] = $this->box->make('Lang');
        $this->setErrors($this['main']['errors']);
        $this->setLanguage($this['main']['localization']['language']);
    }
    
    /**
     * @inheritdoc
     */
    public function setLanguage($lang) {
        $this->inferiors['Lang']->load($lang);
    }
    
    /**
     * @inheritdoc
     */
    public function setErrors(array $params) {
        if (isset($params['display'])) {
            ini_set('display_errors', $params['display']);
            ini_set('display_startup_errors', $params['display']);
        }
        if (isset($params['reporting'])) {
            error_reporting($params['reporting']);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function getDirectoryPath($path) {
        return $this['main']['directories'][$path] . '/';
    }
    
    /**
     * @inheritdoc
     */
    public function getCurrentThemeName() {
        return $this['main']['site']['theme'];
    }
    
    /**
     * @inheritdoc
     */
    public function getCurrentThemePath() {
        return $this->getDirectoryPath('themes') . '/' . $this->getCurrentThemeName() . '/';
    }
}
