<?php

namespace Models;

class ProductsModel extends BaseModel {
  protected static $_table = 'products';

  protected static function leftJoinCond($cond) {
    return self::$_db->leftJoinCond([
      'products.id',
      'products.code',
      'vendors.name AS vendor',
      'products.name',
      'products.description',
      'products.price',
    ], static::$_table, 'vendors', ['vendor_id' => 'id'], $cond);
  }

  public static function get($id) {
    return static::leftJoinCond(['products.id' => $id])[0];
  }

  public static function getWithCategory($category) {
    return static::leftJoinCond(['category_id' => $category]);
  }

  public static function getWithCode($code) {
    return static::leftJoinCond(['code' => $code]);
  }

  public static function getPrice($product_id) {
    return self::$_db->select(['price'], static::$_table, ['id' => $product_id])[0]['price'];
  }
}
