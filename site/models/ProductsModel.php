<?php

namespace Models;

use Tools\Functions;

class ProductsModel extends BaseModel {
  protected static $table = 'products';
  protected static $selectors = Array(
    'products.id',
    'products.code',
    'vendors.name AS vendor',
    'products.name',
    'products.description',
    'products.price',
  );

  protected static function selectByFieldValue() {
    return self::$db->select(static::$selectors)->from(static::$table)->leftJoin('vendors')->on('vendor_id', 'id');
  }

  public static function countByCategory($category_id) {
//    return self::$db->select('COUNT(*)')->from(static::$table)->whereE('category_id', $category_id)->assoc()[0]['COUNT(*)'];
    $out = self::$db->select('COUNT(*)')->from(static::$table)->whereE('category_id', $category_id)->assoc();
    return $out[0]['COUNT(*)'];
  }
  
  public static function getByCategoryRange($value, $start, $end) {
    return self::$db->select(static::$selectors)->from(static::$table)->leftJoin('vendors')->on('vendor_id', 'id')
//      ->whereE('category_id', $value)->s('and')->s('products.id')->between($start, $end)->assoc();
      ->whereE('category_id', $value)->limit($start, $end)->assoc();
  }

  public static function getById($id) {
//    return static::selectByFieldValue()->whereE('products.id', $id)->assoc()[0];
    $out = static::selectByFieldValue()->whereE('products.id', $id)->assoc();
    return $out[0];
  }

  public static function getByCategory($category) {
    return static::selectByFieldValue()->whereE('category_id', $category)->assoc();
  }

  public static function getByCode($code) {
    return static::selectByFieldValue()->whereE('code', $code)->assoc();
  }

  public static function getByCodeLike($code) {
    return static::selectByFieldValue()->where('code')->likeAny($code)->limit(0, 10)->assoc();
  }

  public static function getLikeRange($str, $start, $end) {
    //Where `field1` LIKE '%".$search."%' OR `field2` LIKE '%".$search."%' OR `field3` LIKE '%".$search."%'
    return static::selectByFieldValue()->where('products.code')->likeAny(Functions::leaveLettersAndNumbers($str))->s(' OR products.name ')->likeAny($str)
        ->s(' OR products.description ')->likeAny($str)->limit($start, $end)->assoc();
  }

  public static function countSearch($str) {
//    return self::$db->select('COUNT(*)')->from(static::$table)->whereE('category_id', $category_id)->assoc()[0]['COUNT(*)'];
    $out = self::$db->select('COUNT(*)')->from(static::$table)->where('products.code')->likeAny(Functions::leaveLettersAndNumbers($str))->s(' OR products.name ')->likeAny($str)
        ->s(' OR products.description ')->likeAny($str)->assoc();
    return $out[0]['COUNT(*)'];
  }

  public static function getPrice($product_id) {
//    return self::$db->select('price')->from(static::$table)->whereE('id', $product_id)->assoc()[0]['price'];
    $out = self::$db->select('price')->from(static::$table)->whereE('id', $product_id)->assoc();
    return $out[0]['price'];
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
