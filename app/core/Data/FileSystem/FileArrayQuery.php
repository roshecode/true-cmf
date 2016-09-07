<?php

namespace Truth\Data\FileSystem;

use InvalidArgumentException;
use Truth\Data\FileSystem\Exceptions\InvalidFileException;

class FileArrayQuery extends ArrayQuery
{
    /**
     * FileArray constructor.
     *
     * @param string $filePath
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
                throw new InvalidFileException('exceptions.invalid_file');  // TODO: Envisage
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
    public function load($filePath) {// Except::notArray($array, 'InvalidFile')->data($filePath, 'ARRAY');
        // Envisage::notArray($array)->serve();
        // Envisage::notArray($array)->serve([]);
        // Envisage::notArray($array, 'InvalidFile');
        $array = FS::insert($filePath);
        if (is_array($array)) {
            unset($this->array);
            $this->array = $array;
        } else {
            throw new InvalidFileException('exceptions.invalid_file'); // TODO: Envisage
        }
    }
}