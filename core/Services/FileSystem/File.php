<?php

namespace T\Support\Services\FileSystem;

class File
{
    const PHP   = 0;
    const CSV   = 1;
    const INI   = 2;
    const XML   = 3;
    const JSON  = 4;
    const YAML  = 5;

    protected $path;
    protected $data;
    protected $type;
    protected $lastChange;

    /**
     * FileType constructor.
     * @param string $filePath
     */
    public function __construct($filePath, $type = self::PHP)
    {
        $this->size = filesize($this->data);
        $this->lastChange = filectime($this->data);
//        $this->data = $this->type($filePath;
        $this->type = $type;
    }

    public function size() {
        return $this->size;
    }

    public function lastChange() {
        return $this->lastChange;
    }

    public function isChanged() {
        $currentChange = filectime($this->data);
        if ($currentChange == $this->lastChange) {
            $this->lastChange = $currentChange;
            return true;
        } else {
            return false;
        }
    }
}
