<?php
namespace Truecode\ArrayObject;

use T\Interfaces\FS;

class MultiFileArrayObject extends FileArrayObject
{
    public function __construct(FS $fileSystem, $filePaths = null, $separator = '.') {
        if ($filePaths) {
            $multiData = [];
            if (is_array($filePaths)) {
                $filePath = array_pop($filePaths);
                parent::__construct($fileSystem, $filePath, $separator);
                $multiData[$this->fileSystem->filename($filePath)] = &$this->data;
                foreach ($filePaths as $filePath)
                    $multiData[$this->fileSystem->filename($filePath)] = $fileSystem->insert($filePath);
                $this->data = &$multiData;
            } else {
                $this->fileSystem = $fileSystem;
                $this->separator  = $separator;
                if ($this->fileSystem->isDir($filePaths)) {
                    $this->loadFiles($filePaths); // TODO: change name into load
                } else {
                    parent::load($filePaths);
                    $multiData[$this->fileSystem->filename($filePaths)] = &$this->data;
                    $this->data                                         = &$multiData;
                }
            }
        } else {
            $this->fileSystem = $fileSystem;
            $this->separator  = $separator;
        }
    }
    
    /**
     * @param string $filePaths
     */
    public function loadFiles($filePaths) {
        $this->data = $this->fileSystem->insertAll($filePaths);
    }
}
