<?php
namespace T\Services\ArrayObject;

use T\Services\FileSystem\FS;

class FileArrayObject extends ArrayObject
{
    /**
     * @var FS $fileSystem
     */
    protected $fileSystem;
    
    /**
     * FileRepository constructor.
     *
     * @param FS     $fileSystem
     * @param string $filePath
     * @param string $separator
     */
    public function __construct(FS &$fileSystem, $filePath, $separator = '.') {
        $this->fileSystem = $fileSystem;
        parent::__construct($filePath, $separator);
    }
    
    /**
     * @param string $filePath
     *
     * @return array $data
     */
    public function load($filePath) {
        return parent::load($this->fileSystem->insert($filePath));
    }
    
    public function getBasedir() {
        return $this->fileSystem->getBasedir();
    }
}
