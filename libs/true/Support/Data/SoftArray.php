<?php

namespace True\Support\Data;

class SoftArray implements \ArrayAccess
{
    /**
     * Data array
     *
     * @var array $data
     */
    protected $data;

    /**
     * Query string delimiter
     *
     * @var string $delimiter
     */
    protected $delimiter;

    /**
     * Last used query string
     *
     * @var string $query
     */
    protected $query;

    /**
     * Array of keys to get the value
     *
     * @var array $path
     */
    protected $path;

    /**
     * Last index in path array
     *
     * @var integer $length
     */
    protected $lastIndex;

    /**
     * Last got value
     *
     * @var string $sample
     */
    protected $sample;

    /**
     * ArraySeparatorQuery constructor.
     *
     * @param array  $data
     * @param string $delimiter
     */
    public function __construct(array $data = [], string $delimiter = '.')
    {
        $this->delimiter = $delimiter;
        $this->loadArray($data);
    }

    public static function fromArray(array $data)
    {
        return new static($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function loadArray(array $data)
    {
        return $this->data = $data;
    }

    /**
     * Return query string
     *
     * @return string
     */
    public function getLastQuery() : string
    {
        return $this->query;
    }

    /**
     * Recursively getting value from array by query
     *
     * @param array   $array
     * @param mixed   $default
     * @param integer $offset
     * @return mixed
     */
    protected function getValueRecursively(array $array, $default, $offset)
    {
        $next = &$array[$this->path[$offset]];

        return $offset < $this->lastIndex
            ? $this->getValueRecursively($next, $default, ++$offset)
            : ($next === null ? $default : $next);
    }

    /**
     * Recursively getting array item reference by query
     *
     * @param array   $array
     * @param integer $offset
     * @return mixed
     */
    protected function &getReferenceRecursively(array &$array, $offset)
    {
        $next = &$array[$this->path[$offset]] ?? [];

        if ($offset < $this->lastIndex) {
            return $this->getReferenceRecursively($next, ++$offset);
        }

        return $next;
    }

    /**
     * Select value by query
     *
     * @param string $query
     * @param mixed  $default
     * @return mixed
     */
    public function get(string $query, $default = null)
    {
//        if ($this->query === $query) {
//            return $this->sample;
//        } else {
        $this->query = $query;
        $this->path = explode($this->delimiter, $query);
        $this->lastIndex = count($this->path) - 1;

//        }
        return $this->sample = $this->getValueRecursively($this->data, $default, 0);
    }

    /**
     * Set value by query
     *
     * @param string $query
     * @param mixed  $value
     */
    public function set(string $query, $value)
    {
        $this->query = $query;
        $this->path = explode($this->delimiter, $query);
        $this->lastIndex = count($this->path) - 1;
        $placeholder = &$this->getReferenceRecursively($this->data, 0);
        $placeholder = $value;
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
        return $this->data[$offset] ?? null;
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
