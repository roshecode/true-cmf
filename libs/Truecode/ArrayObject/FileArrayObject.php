<?php
namespace Truecode\ArrayObject;

use T\Interfaces\FSInterface;

class FileArrayObject extends ArrayObject
{
    /**
     * @var FSInterface $fileSystem
     */
    protected $fileSystem;
    
    /**
     * FileRepository constructor.
     *
     * @param FSInterface $fileSystem
     * @param string      $filePath
     * @param string      $separator
     */
    public function __construct(FSInterface $fileSystem, $filePath, $separator = '.') {
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
