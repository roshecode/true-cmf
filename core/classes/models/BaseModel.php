<?php

namespace Models;
use Data\Dbi;
use Tools\Functions;

/**
 * Class BaseModel
 * @property $id
 */
abstract class BaseModel {
  /** @var Dbi */
//  protected static $db;
  public static $db;
  protected static $table = 'unknown';
  protected $data = Array();
  
  public static function init()
  {
    self::$db = Dbi::getInstance();
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
  public static function count() {
//    return self::$db->select('COUNT(*)')->from(static::$table)->assoc()[0]['COUNT(*)'];
    $out = self::$db->select('COUNT(*)')->from(static::$table)->assoc();
    return $out[0]['COUNT(*)'];
  }
  public static function getAll()
  {
    return self::$db->select('*')->from(static::$table)->assoc();
  }
  public static function getById($id)
  {
//    return self::$db->select('*')->from(static::$table)->whereE('id', $id)->assoc()[0];
    $out = self::$db->select('*')->from(static::$table)->whereE('id', $id)->assoc();
    return $out[0];
  }
  public static function removeById($id)
  {
    return self::$db->delete()->from(static::$table)->whereE('id', $id)->run();
  }
  public static function removeAll()
  {
    return self::$db->truncate(static::$table);
  }
  public static function add($data)
  {
    return self::$db->insertInto(static::$table, array_keys($data))->values(array_values($data))->run();
  }
  public static function update($field, $value, $data)
  {
    return self::$db->update(static::$table)->set($data)->whereE($field, $value)->run();
  }
  public static function save($data, $field1 = 'id', $field2 = 'id')
  {
    return self::$db->insertInto(static::$table, array_keys($data))->values(array_values($data))
      ->onDuplicateKeyUpdate($field1, $field2)->run();
  }
}
