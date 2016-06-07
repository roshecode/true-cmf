<?php

namespace Models;

class ProductsModel extends BaseModel {
  protected static $_table = 'products';
  
  public static function get($category) {
    return self::$_db->select(static::$_table, ['*'], ['category_id' => $category]);
  }
}
