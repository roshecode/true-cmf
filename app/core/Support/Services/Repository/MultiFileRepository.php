<?php

namespace T\Support\Services\Repository;

use T\Support\Services\FileSystem\FS;

class MultiFileRepository extends FileRepository
{
    public function __construct(FS $fileSystem, $filePaths, $separator = '.')
    {
        $multiData = [];
        if (is_array($filePaths)) {
            $filePath = array_pop($filePaths);
            parent::__construct($fileSystem, $filePath, $separator);
            $multiData[$this->filename($filePath)] = &$this->data;
            foreach ($filePaths as $filePath) {
                $multiData[$this->filename($filePath)] = $fileSystem->insert($filePath);
            }
        } else {
            parent::__construct($fileSystem, $filePaths, $separator);
            $multiData[$this->filename($filePaths)] = &$this->data;
        }
        $this->data = &$multiData;
    }

    protected function filename($filePath) {
        return pathinfo($filePath, PATHINFO_FILENAME);
    }
}