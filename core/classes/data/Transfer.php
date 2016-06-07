<?php

namespace Data;
use PDOException;

class Transfer {

  private static function correct($str) {
    return strtr(preg_split('/(\s[a-zA-Z])|([,;:])/', $str)[0],
      'aeopcyxABEHOPCTX', 'аеорсухАВЕНОРСТХ');
  }

  public static function import($file, $settings) {
    $dbh = DB::getInstance();
    if (file_exists($file) && is_readable($file)) {
      if ($fh = fopen($file, "rb")) {
        $delimiter = $settings['delimiter'];
        $categoryIndex = 0;
        $vendorIndex = 0;
        if (!feof($fh)) {
          fgets($fh);
        }
        while (!feof($fh)) {
          $row = explode($delimiter, fgets($fh));
          ?><pre><?php
          echo $row[3] . PHP_EOL;
          ?></pre><?php

          ++$vendorIndex;
          ++$categoryIndex;
          $vendorName = trim($row[1]);
          $categoryName = trim(self::correct($row[3]));
          try {
            $dbh->insert('vendors', [
              'id' => $vendorIndex,
              'name' => $vendorName
            ]);
          } catch (PDOException $e) {
            --$vendorIndex;
          }
          try {
            $dbh->insert('categories', [
              'id' => $categoryIndex,
              'name' => $categoryName
            ]);
          } catch (PDOException $e) {
            --$categoryIndex;
          }
          $dbh->insert('products', [
            'code' => preg_replace ('/[^a-zA-ZА-Яа-я0-9]/', '', $row[0]),
            'vendor_id' => $dbh->select('vendors', ['id'], ['name' => $vendorName])[0]['id'],
            'category_id' => $dbh->select('categories', ['id'], ['name' => $categoryName])[0]['id'],
            'name' => $row[2],
            'description' => $row[3],
            'price' => doubleval(str_replace(',', '.', $row[4]))
          ]);
        }
      }
      else {
        echo "Ошибка при открытии файла!";
      }
      fclose($fh);
    }
  }
}
