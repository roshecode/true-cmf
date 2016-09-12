<?php

namespace Truth\Support\Services\Repository;

use InvalidArgumentException;
use Truth\Support\Abstracts\ServiceProvider;

class Repository extends ServiceProvider
{
    /**
     * Some array
     *
     * @var array
     */
    protected $data;
    /**
     * Query string separator
     *
     * @var string
     */
    protected $separator;
    /**
     * Last used query string
     *
     * @var string
     */
    protected $query;
    /**
     * Array of path to value
     *
     * @var array
     */
    protected $path;
    /**
     * Last got value
     *
     * @var string
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
        if (is_array($array) && is_string($separator)) {
            $this->data = $array;
            $this->separator = $separator;
        } else {
            throw new InvalidArgumentException('exceptions.invalid_argument'); // TODO: Envisage
        }
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
     * @return mixed|null
     */
    protected function getValue($array, $offset) {
        if ($offset !== count($this->path)) {
            if (array_key_exists($this->path[$offset], $array)) {
                return $this->getValue($array[$this->path[$offset]], ++$offset);
            } else {
//            trigger_error(Lang['backend']->get('notices.key_does_not_exist'));
                trigger_error('notices.key_does_not_exist');
                return null;
            }
        }
        return $array;
    }

    /**
     * Select value by query
     *
     * @param $query
     * @param int $offset
     * @return mixed|null|string
     *
     * @throws InvalidArgumentException
     */
    public function get($query, $offset = 0) {
        if (is_string($query)) {
            if ($this->query === $query) {
                return $this->sample;
            } else {
                $this->query = $query;
                $this->path = explode($this->separator, $query);
            }
        } else {
            throw new InvalidArgumentException('exceptions.invalid_argument'); // TODO: Envisage
        }
        if (is_numeric($offset)) {
            return $this->sample = $this->getValue($this->data, $offset);
        } else {
            throw new InvalidArgumentException('exceptions.invalid_argument'); // TODO: Envisage
        }
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
}
