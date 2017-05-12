<?php
namespace T\Services\ArrayObject;

use T\Interfaces\Filesystem;

class FileArrayObject extends ArrayObject
{
    /**
     * @var Filesystem $fileSystem
     */
    protected $fileSystem;
    
    /**
     * FileRepository constructor.
     *
     * @param Filesystem     $fileSystem
     * @param string $filePath
     * @param string $separator
     */
    public function __construct(Filesystem &$fileSystem, $filePath, $separator = '.') {
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
