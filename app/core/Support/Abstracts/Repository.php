<?php

namespace Truth\Support\Abstracts;

use Truth\Data\FileSystem\FS;

abstract class Repository extends ServiceProvider
{
    /**
     * @var \Truth\Data\FileSystem\FileArrayQuery
     */
    protected $data;

    public function __construct($filePath) {
        $this->data = FS::getAssoc(APP_DIR . $filePath);
    }

    public function get($query) {
        return $this->data->get($query);
    }

    public function set($query, $value) {
        $this->data->set($query, $value);
    }

    // TODO: delete this function
    public function getAll() {
        return $this->data;
    }
}
