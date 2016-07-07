<?php

namespace Models;

class PageModel extends BaseModel {
  protected static $table = 'pages';

  public static function upd($id, $data) {
    return self::$db->insertInto(static::$table, array_keys($data))->values(array_values($data))
      ->whereE('id', $id)->run();
  }
}
