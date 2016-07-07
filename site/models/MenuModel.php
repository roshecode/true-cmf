<?php

namespace Models;

class MenuModel extends BaseModel {
  protected static $table = 'menu';

  public static function getIdByAlias($alias) {
    $out = self::$db->select('id')->from(static::$table)->whereE('alias', $alias)->assoc();
    return $out[0]['id'];
  }
}
