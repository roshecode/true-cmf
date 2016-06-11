<?php

namespace Models;

class CategoriesModel extends BaseModel {
  protected static $_table = 'categories';
  
  public static function getAll() {
    return self::$_db->selectAllOrderBy(static::$_table, '`order`');
  }
  
  public static function getIdWithOrder($order) {
    return self::$_db->select(['id'], static::$_table, ['`order`' => $order])[0]['id'];
  }

  public static function getOrderWithId($id) {
    return self::$_db->select(['`order`'], static::$_table, ['id' => $id])[0]['order'];
  }
}
