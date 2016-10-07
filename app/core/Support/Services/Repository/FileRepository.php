<?php

namespace T\Support\Services\Repository;

use T\Support\Services\FileSystem\FS;

class FileRepository extends Repository
{
    /**
     * @var \T\Support\Services\FileSystem\FS $fileSystem
     */
    protected $fileSystem;

    /**
     * FileRepository constructor.
     *
     * @param FS $fileSystem
     * @param string $filePath
     * @param string $separator
     */
    public function __construct(FS &$fileSystem, $filePath, $separator = '.') {
        $this->fileSystem = $fileSystem;
        parent::__construct($fileSystem->insert($filePath), $separator);
    }

    public function getBasedir() {
        return $this->fileSystem->getBasedir();
    }
}
