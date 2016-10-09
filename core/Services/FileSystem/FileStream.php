<?php

namespace T\Services\FileSystem;

class FileStream
{
    const OPEN_READ = 'r';
    const OPEN_READ_WRITE_PREPEND = 'r+';
    const OPEN_CREATE_WRITE_UPDATE = 'w';
    const OPEN_CREATE_WRITE_PREPEND = 'c';
    const OPEN_CREATE_WRITE_APPEND = 'a';
    const OPEN_CREATE_READ_WRITE_UPDATE = 'w+';
    const OPEN_CREATE_READ_WRITE_PREPEND = 'c+';
    const OPEN_CREATE_READ_WRITE_APPEND = 'a+';
    const CREATE_WRITE = 'x';
    const CREATE_READ_WRITE = 'x+';





    protected $handle;

    public function __construct($filePath, $mode = self::OPEN_READ)
    {
        $this->handle = fopen($filePath, $mode . 'b');
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    public function clear() {
        ftruncate($this->handle, 0);
    }
}