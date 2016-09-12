<?php

namespace Truth\Support\Services\Repository;

class FileRepository extends Repository
{
    /**
     * @var \Truth\Support\Services\FileSystem\FS $fileSystem
     */
    protected $fileSystem;

    /**
     * FileRepository constructor.
     *
     * @param \Truth\Support\Services\FileSystem\FS $fileSystem
     * @param string $filePath
     * @param string $separator
     */
    public function __construct(&$fileSystem, $filePath, $separator = '.') {
        $this->fileSystem = $fileSystem;
        parent::__construct($fileSystem->insert($filePath), $separator);
    }

    public function getBasedir() {
        return $this->fileSystem->getBasedir();
    }
}
