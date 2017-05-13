<?php
namespace Truecode\ArrayObject;

use ArrayAccess;
use InvalidArgumentException;

class ArrayObject implements ArrayAccess
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
    protected $placeholders;
    
    /**
     * ArraySeparatorQuery constructor.
     *
     * @param string|array $data
     * @param string       $separator
     *
     * @throws InvalidArgumentException
     */
    public function __construct($data, $separator = '.') {
        $this->separator = $separator;
        $this->load($data);
    }
    
    /**
     * @param array $data
     *
     * @return array $data
     */
    public function load($data) {
        return $this->data = $data;
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
     * @param array   $array
     * @param integer $offset
     *
     * @return mixed
     */
    protected function getValue(array $array, $offset) {
        $next = &$array[$this->path[$offset]];
        return $offset < $this->end && isset($next) ? $this->getValue($next, ++$offset) : $next;
    }
    
    /**
     * Select value by query
     *
     * @param string $query
     *
     * @return mixed
     */
    public function get(string $query) {
//        if ($this->query === $query) {
//            return $this->sample;
//        } else {
        $this->query = $query;
        $this->path  = explode($this->separator, $query);
        $this->end   = count($this->path) - 1;
//        }
        return $this->sample = $this->getValue($this->data, 0);
    }
    
    /**
     * Recursively getting array item reference by query
     *
     * @param array   $array
     * @param integer $offset
     *
     * @return mixed
     */
    protected function &setValue(array $array, $offset) {
        $next = &$array[$this->path[$offset]];
//        return ($offset < $this->end) ?
//            $this->setValue(isset($next) ? $next : $next = [], ++$offset) : $next;
        if ($offset < $this->end) {
            return $this->setValue(isset($next) ? $next : $next = [], ++$offset);
        } else {
            return $next;
        }
    }
    
    /**
     * Set value by query
     *
     * @param string $query
     * @param mixed $value
     */
    public function set(string $query, $value) {
        $this->query = $query;
        $this->path  = explode($this->separator, $query);
        $this->end   = count($this->path) - 1;
        $placeholder = &$this->setValue($this->data, 0);
        $placeholder = $value;
    }
    
    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     *
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }
    
    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
    
    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) {
        is_null($offset) ? $this->data[] = $value : $this->data[$offset] = $value;
    }
    
    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
}
