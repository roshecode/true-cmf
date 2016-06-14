<?php

namespace Models;

class MenuModel extends BaseModel {
  protected static $table = 'menu';

  public static function getIdByAlias($alias) {
    return self::$db->select('id')->from(static::$table)->where('alias', $alias)->assoc()[0]['id'];
  }
}
