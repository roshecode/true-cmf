<?php

namespace Models;

class ProductsModel extends BaseModel {
  protected static $_table = 'products';
  
  public static function get($category) {
//    return self::$_db->select(['*'], static::$_table, ['category_id' => $category]);
    return self::$_db->leftJoinCond([
      'products.id',
      'products.code',
      'vendors.name AS vendor',
      'products.name',
      'products.description',
      'products.price',
    ], static::$_table, 'vendors', ['vendor_id' => 'id'], ['category_id' => $category]);
  }

  public static function getPrice($product_id) {
    return self::$_db->select(['price'], static::$_table, ['id' => $product_id])[0]['price'];
  }
}
