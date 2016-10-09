<?php

namespace T\Support\Services\Routing\TrueRouter;

class Splitter implements \ArrayAccess
{
    const STEADY = 0;
    const VARIED = 1;
    const REGEXP = 2;

//    protected $data;

    public function __get($name) {
//        $class = __NAMESPACE__ . '\\' . $name;
//        $this->$name = new $class;
        return $this->$name = new self;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * An offset to check for.
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * The offset to retrieve.
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if ($offset[0] === ':') {
            $parts_regexp = explode(':', $offset, 3);
            if (isset($parts_regexp[2])) {
                $regex = $parts_regexp[2];
            } else {
                $parts_filter = explode('|', $parts_regexp[1], 2);
                if (isset($parts_filter[1])) {
                    $parts_regexp[1] = $parts_filter[0];
                    $regex = preg_replace_callback('/\+|\w+/i', function($matches) {
                        return $matches[0] == '+' ? '' : trim(Router::getFilter($matches[0]), '^$');
                    }, $parts_filter[1]);
                } else $regex = '';
            }
            $placeholder = &$this->data[self::VARIED][$parts_regexp[1]];
            //$placeholder= new Splitter;
//            $placeholder->setMatch('/' . $regex . '/');
            return $placeholder;
        } else {
            return $this->data[self::STEADY][$offset];// = new Splitter;
        }
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * The offset to assign the value to.
     * @param mixed $value
     * The value to set.
//     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {

    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * The offset to unset.
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}