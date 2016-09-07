<?php

namespace Truth\Support\Abstracts;

abstract class FileRepository extends ServiceProvider
{
    /**
     * @var \Truth\Support\Services\FileSystem\FS $fileSystem
     */
    protected $fileSystem;
    /**
     * @var \Truth\Support\Services\FileSystem\FileArrayQuery
     */
    protected $data;

    /**
     * FileRepository constructor.
     *
     * @param \Truth\Support\Services\FileSystem\FS $fileSystem
     * @param $filePath
     */
    public function __construct($fileSystem, $filePath) {
        $this->fileSystem = $fileSystem;
        $this->data = $fileSystem->getAssoc($filePath);
    }

    public function get($query) {
        return $this->data->get($query);
    }

    public function set($query, $value) {
        $this->data->set($query, $value);
    }
}
