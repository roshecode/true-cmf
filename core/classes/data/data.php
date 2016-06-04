<?php

use Data\DB;

class Data {
  static function import($file, $settings) {
    $db = DB::getInstance();
    if (file_exists($file) && is_readable($file)) {
      if ($fh = fopen($file, "rb")) {
        $delimiter = $settings['delimiter'];
        $i = 0;
        while (!feof($fh)) {
          $row = explode($delimiter, fgets($fh));

          try {
            $db->insert('categories', [
              'id' => ++$i,
              'name' => preg_split('/(\s[a-zA-Z])|([,;:])/', $row[3])[0]
            ]);
          } catch (PDOException $e) {
            --$i;
          }
        }
      }
      else echo "Ошибка при открытии файла!";
      fclose($fh);
    }
  }
}
