<?php

namespace Models;

class CategoriesModel extends BaseModel {
  protected static $table = 'categories';
  
  public static function getAll() {
//    return self::$_db->selectAllOrderBy(static::$_table, '`order`');
    return self::$db->select('*')->from(static::$table)->orderBy('`order`')->assoc();
  }

  public static function getIdByName($name) {
    return self::$db->getIdByName(static::$table, $name);
  }
  
//  public static function getIdByOrder($order) {
//    return self::$_db->select(['id'], static::$_table, ['`order`' => $order])[0]['id'];
//  }
//
//  public static function getOrderById($id) {
//    return self::$_db->select(['`order`'], static::$_table, ['id' => $id])[0]['order'];
//  }
}
