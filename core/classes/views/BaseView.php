<?php

namespace Views;

class BaseView implements \Iterator {
  protected static $_template = 'unknown';
  protected $data = [];
  public static $items = [];

  public function __set($name, $value) {
    $this->data[$name] = $value;
  }
  public function __get($name) {
    return $this->data[$name];
  }

  public static function display($items = null)
  {
    static::$items = $items;
//    foreach ($this->data as $key => $val) {
//      $$key = $val;
//    }
    include TEMPLATE . static::$_template;
  }

  public function current()
  {
    return current($this->data);
  }
  public function next()
  {
    return next($this->data);
  }
  public function key()
  {
    return key($this->data);
  }
  public function valid()
  {
    $key = key($this->data);
    return $key !== NULL && $key !== FALSE;
  }
  public function rewind()
  {
    reset($this->data);
  }
}
