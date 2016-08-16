<?php

namespace True\Data\FileSystem;

use InvalidArgumentException;
use True\Exceptions\InvalidFileException;
use True\Support\Facades\Lang;

class FileArrayQuery extends ArrayQuery
{
    /**
     * FileArray constructor.
     *
     * @param $filePath
     * @param string $separator
     *
     * @throws InvalidFileException
     * @throws InvalidArgumentException
     */
    public function __construct($filePath, $separator = '.')
    {
        try {
            parent::__construct(FS::insert($filePath), $separator);
        } catch (InvalidArgumentException $e) {
            if (is_string($separator)) {
                throw new InvalidFileException(Lang::get('exceptions.invalid_file'));
            } else {
                throw new InvalidArgumentException($e->getMessage());
            }
        }
    }

    /**
     * Load array from file.
     *
     * @param string $filePath
     *
     * @throws InvalidFileException
     */
    public function load($filePath) {
        $array = FS::insert($filePath);
        if (is_array($array)) {
            $this->array = $array;
        } else {
            throw new InvalidFileException(Lang::get('exceptions.invalid_file'));
        }
    }
}