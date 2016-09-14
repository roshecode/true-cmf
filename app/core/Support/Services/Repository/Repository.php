<?php

namespace Truth\Support\Services\Repository;

use ArrayAccess;
use InvalidArgumentException;
use Truth\Support\Abstracts\ServiceProvider;

class Repository extends ServiceProvider implements ArrayAccess
{
    /**
     * Some array
     *
     * @var array $data
     */
    protected $data;
    /**
     * Query string separator
     *
     * @var string $separator
     */
    protected $separator;
    /**
     * Last used query string
     *
     * @var string $query
     */
    protected $query;
    /**
     * Array of path to value
     *
     * @var array $path
     */
    protected $path;
    /**
     * Path length subtract one
     *
     * @var integer $length
     */
    protected $end;
    /**
     * Last got value
     *
     * @var string $sample
     */
    protected $sample;

    /**
     * ArraySeparatorQuery constructor.
     *
     * @param $array
     * @param string $separator
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $array, $separator = '.') {
        $this->data = $array;
        $this->separator = $separator;
    }

    /**
     * Return query string
     *
     * @return string
     */
    public function getLastQuery() {
        return $this->query;
    }

    /**
     * Recursively getting value from array by query
     *
     * @param array $array
     * @param integer $offset
     * @return mixed
     */
    protected function getValue(array $array, $offset) {
        return $offset < $this->end ? $this->getValue($array[$this->path[$offset]], ++$offset) :
            $array[$this->path[$offset]];
    }

    /**
     * Select value by query
     *
     * @param $query
     * @param int $offset
     * @return mixed
     */
    public function get($query, $offset = 0) {
//        if ($this->query === $query) {
//            return $this->sample;
//        } else {
            $this->query = $query;
            $this->path = explode($this->separator, $query);
            $this->end = count($this->path) - 1;
//        }
        return $this->sample = $this->getValue($this->data, $offset);
    }

    /**
     * Set value by query
     *
     * @param $query
     * @param $value
     */
    public function set($query, $value) {
        // TODO: Setting with separator
        $this->data[$query] = $value;
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        is_null($offset) ? $this->data[] = $value : $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
