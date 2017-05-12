<?php
namespace T\Services\Filesystem;

class File
{
    const EXT_PHP  = 'php';
    const EXT_CSV  = 'csv';
    const EXT_INI  = 'ini';
    const EXT_XML  = 'xml';
    const EXT_JSON = 'json';
    const EXT_YAML = 'yml';
    protected $path;
    protected $data;
    protected $type;
    protected $lastChange;
    
    /**
     * FileType constructor.
     *
     * @param string $filePath
     */
    public function __construct($filePath) {
        $this->size       = filesize($this->data);
        $this->lastChange = filectime($this->data);
        $this->data = $this->load($filePath);
    }
    
    public function load($filePath) {
        switch($this->type = strtolower(pathinfo($filePath, PATHINFO_EXTENSION))) {
            case self::EXT_PHP: return include $filePath;
            case self::EXT_INI: return parse_ini_file($filePath, true);
            default: return file_get_contents($filePath);
        }
    }
    
    public function size() {
        return $this->size;
    }
    
    public function lastChange() {
        return $this->lastChange;
    }
    
    public function isChanged() {
        $currentChange = filectime($this->data);
        if ($currentChange == $this->lastChange) {
            $this->lastChange = $currentChange;
            return true;
        } else {
            return false;
        }
    }
}
