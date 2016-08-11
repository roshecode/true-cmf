<?php

namespace True\Data\FileSystem;

final class FileArray
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