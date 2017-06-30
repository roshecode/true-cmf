<?php
namespace T\Services;

use T\Interfaces\LangInterface;
use T\Traits\Servant;
use T\Interfaces\ConfigInterface;
use T\Interfaces\FSInterface;
use Truecode\ArrayObject\MultiFileArrayObject;

class Config extends MultiFileArrayObject implements ConfigInterface
{
    use Servant;

    /**
     * @param FSInterface $fileSystem
     * @param string      $filePaths
     * @param string      $separator
     */
    public function __construct(FsInterface $fileSystem, $filePaths, string $separator = '.') {
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
        $this->box->make(Lang::class)->load($lang);
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
