<?php

namespace Models;
use Data\DB;

/**
 * Class BaseModel
 * @property $id
 */
abstract class BaseModel {
  /** @var DB */
  protected static $_db;
  protected static $_table = 'unknown';
  protected $_data = [];
  
  public static function init()
  {
    self::$_db = DB::getInstance();
  }
//  public function __construct() {
//    self::$_db = DB::getInstance();
//  }

  public function __set($key, $val)
  {
    $this->_data[$key] = $val;
  }
  public function __get($key)
  {
    return $this->_data[$key];
  }
  public static function getAll()
  {
    return self::$_db->queryMap('SELECT * FROM ' . static::$_table);
  }
  public static function get($id)
  {
    return self::$_db->queryMapData('SELECT * FROM ' . static::$_table .' WHERE id = ?', [$id])[0];
  }
  public static function removeAll()
  {
    // TODO: remove all news
  }
  public static function remove($id)
  {
    $db = self::$_db;
    $db->queryData('DELETE FROM ' . static::$_table . ' WHERE id = ?', [$id]);
  }
  public function insert()
  {
//    $q = 'INSERT INTO ' . static::$_table . '(' . implode(',', array_keys($this->data)) .
//      ') VALUES (?' . str_repeat(',?', count($this->data) - 1) . ')';
//    return self::$_db->queryData($q, array_values($this->data));
    return self::$_db->insert(static::$_table, $this->_data);
  }
  public function update()
  {
    $arr = array_keys($this->_data);
    array_pop($arr);
    $q = 'UPDATE ' . static::$_table . ' SET ' . implode('=?,', $arr) . '=? WHERE id=?';
    $data = array_values($this->_data);
    self::$_db->queryData($q, $data);
  }
  public function save()
  {
    if (!$this->insert()) {
      $this->update();
    }
  }
  public function delete()
  {
    self::remove($this->id);
  }
}
