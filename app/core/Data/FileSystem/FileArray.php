<?php

namespace True\Data\FileSystem;

use True\Data\RamSystem\ArrayQuery;
use True\Multilingual\Lang;

class FileArray
{
    /**
     * Loaded array from file.
     *
     * @var array
     */
    protected $data;

    public function __construct($filePath)
    {
        $this->load($filePath);
    }

//    public function __get($name)
//    {
//        return $this->data[$name];
//    }
//
//    public function __set($name, $value)
//    {
//        $this->data[$name] = $value;
//    }

    /**
     * Load array from file.
     *
     * @param string $filePath
     */
    public function load($filePath) {
        $this->data = File::inc($filePath);
    }

    /**
     * Get array value by query
     *
     * @param string $query
     * @return mixed
     */
    public function get($query) {
        $query = new ArrayQuery($query);
        return $query->apply($this->data);
    }

    public function set($query, $value) {
        // TODO: query set implementation
        $this->data[$query] = $value;
    }
}