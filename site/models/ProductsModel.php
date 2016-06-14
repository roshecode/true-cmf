<?php

namespace Models;

class ProductsModel extends BaseModel {
  protected static $table = 'products';

  protected static function selectByFieldValue($field, $value) {
    return self::$db->select([
      'products.id',
      'products.code',
      'vendors.name AS vendor',
      'products.name',
      'products.description',
      'products.price',
    ])->from(static::$table)->leftJoin('vendors')->on('vendor_id', 'id')
      ->where($field, $value)->assoc();
  }

  public static function getById($id) {
    return static::selectByFieldValue('products.id', $id)[0];
  }

  public static function getRage($fromId, $toId) {
//    return static::leftJoinCond(['products.id' => $id])
  }

  public static function getByCategory($category) {
    return static::selectByFieldValue('category_id', $category);
  }

  public static function getByCode($code) {
    return static::selectByFieldValue('code', $code);
  }

  public static function getPrice($product_id) {
    return self::$db->select('price')->from(static::$table)->where('id', $product_id)->assoc()[0]['price'];
  }
}

//class ProductsModel extends BaseModel {
//  protected static $_table = 'products';
//
//  protected static function leftJoinCond($cond) {
//    self::$_db->set('i', 0);
//    return self::$_db->leftJoinCond([
//      'products.id',
//      'products.code',
//      'vendors.name AS vendor',
//      'products.name',
//      'products.description',
//      'products.price',
//    ], static::$_table, 'vendors', ['vendor_id' => 'id'], $cond);
//  }
//
//  public static function get($id) {
//    return static::leftJoinCond(['products.id' => $id])[0];
//  }
//
//  public static function getRage($fromId, $toId) {
////    return static::leftJoinCond(['products.id' => $id])
//  }
//
//  public static function getWithCategory($category) {
//    return static::leftJoinCond(['category_id' => $category]);
//  }
//
//  public static function getWithCode($code) {
//    return static::leftJoinCond(['code' => $code]);
//  }
//
//  public static function getPrice($product_id) {
//    return self::$_db->select(['price'], static::$_table, ['id' => $product_id])[0]['price'];
//  }
//}
