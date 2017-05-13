<?php
namespace T\Services\Config;

use T\Interfaces\Lang;
use T\Traits\Service;
use T\Interfaces\Config as ConfigInterface;
use T\Interfaces\FS;
use Truecode\ArrayObject\MultiFileArrayObject;

class Config extends MultiFileArrayObject implements ConfigInterface
{
    use Service;

    /**
     * @param FS     $fileSystem
     * @param string $filePaths
     * @param string $separator
     */
    public function __construct(FS $fileSystem, $filePaths, string $separator = '.') {
        parent::__construct($fileSystem, $filePaths, $separator);
    }
    
    public function boot() {
        $this->setErrors($this['main']['errors']);
        $this->setLanguage($this['main']['localization']['language']);
    }
    
    /**
     * @inheritdoc
     */
    public function setLanguage(string $lang) {
        $this->box[Lang::class]->load($lang);
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
    public function getDirectoryPath($path) : string {
        return $this['main']['directories'][$path] . '/';
    }
    
    /**
     * @inheritdoc
     */
    public function getCurrentThemeName() : string {
        return $this['main']['site']['theme'];
    }
    
    /**
     * @inheritdoc
     */
    public function getCurrentThemePath() : string {
        return $this->getDirectoryPath('themes') . '/' . $this->getCurrentThemeName() . '/';
    }
}
