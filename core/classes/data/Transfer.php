<?php

namespace Data;
use PDOException;
use Tools\Functions;

class Transfer {

  public static function import($file, $settings) {
    $dbh = DB::getInstance();
    if (file_exists($file) && is_readable($file)) {
      if ($fh = fopen($file, "rb")) {
        $delimiter = $settings['delimiter'];
        $categoryId = 1;
        $vendorId = 1;
        if (!feof($fh)) {
          fgets($fh);
        }
//        $i = 0;
        while (!feof($fh) /*&& $i < 20*/) {
//          ++$i;
          $row = explode($delimiter, fgets($fh));
          $vendor = trim($row[1]);
          $category = trim(Functions::replaceSameLettersEnToRus($row[3]));
          echo $category;

          $dbh->save('vendors', ['name' => $vendor]);
//          $dbh->save('vendors', [
//            'id' => $vendorId,
//            'name' => $vendor
//          ], ['vendor' => 'vendor']);

          $dbh->save('categories', ['name' => $category]);
//          $dbh->save('categories', [
//            'id' => $categoryId,
//            'name' => $category
//          ], ['category' => 'category']);

          $dbh->insert('products', [
            'code' => Functions::leaveLettersAndNumbers($row[0]),
            'vendor_id' => $dbh->getIdOfName('vendors', $vendor),
            'category_id' => $dbh->getIdOfName('categories', $category),
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
