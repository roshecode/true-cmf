<?php

namespace Data;
use Functions\Func;
use Models\CategoriesModel;
use PDOException;
use Tools\Functions;

class Transfer {

  private static $dbh;
  private static $insertIntoVendors;
  private static $insertIntoCategories;
  private static $insertIntoProducts;
  private static $getIdByVendor;
  private static $getIdByCategory;

  public static function init() {
    self::$dbh = Dbi::getInstance();
    self::$insertIntoVendors = self::$dbh->insertInto('vendors', Array('name'))->values(Array(0))
        ->onDuplicateKeyUpdate('id', 'id')->statement();
    self::$getIdByVendor = self::$dbh->select('id')->from('vendors')->whereE('name', '')->statement();
    self::$insertIntoCategories = self::$dbh->insertInto('categories', Array('parent_id', '`order`', 'name'))
        ->values(Array(0, 0, 0))->onDuplicateKeyUpdate('id', 'id')->statement();
    self::$getIdByCategory = self::$dbh->select('id')->from('categories')->whereE('name', '')->statement();
    self::$insertIntoProducts = self::$dbh->insertInto('products', Array(
        'code',
        'vendor_id',
        'category_id',
        'name',
        'description',
        'price'))
        ->values(Array(0, 0, 0, 0, 0, 0))->onDuplicateKeyUpdate('price', 'price')->statement();
  }

  public static function import1($row, $parentId) {
//    $category = Functions::cutOnlyFirstRusWords($row[2]);
//    $category = ucfirst(trim(Functions::replaceSameLettersEnToRus($category)));
//    self::$insertIntoCategories->execute(Array($parentId, 0, $category));
    $vendor = trim($row[1]);
    self::$insertIntoVendors->execute(Array($vendor));
    self::$getIdByVendor->execute(Array($vendor));
    $vendor_id = self::$getIdByVendor->fetchAll(\PDO::FETCH_ASSOC);
//    self::$getIdByCategory->execute(Array($category));
//    $category_id = self::$getIdByCategory->fetchAll(\PDO::FETCH_ASSOC);
    self::$insertIntoProducts->execute(Array(
        Functions::leaveLettersAndNumbers($row[0]),
        $vendor_id[0]['id'],
//        $category_id[0]['id'],
        1,
        $row[2],
        $row[3],
        doubleval(str_replace(',', '.', $row[4]))));
  }

  public static function importOther($row, $parentId, $index) {
    $code = Functions::leaveLettersAndNumbers($row[$index]);
    $name = $row[$index + 1];
    $price = doubleval(str_replace(',', '.', $row[$index + 3]));
    self::$insertIntoProducts->execute(Array(
        $code,
        null,
        $parentId,
        $name,
        null,
        $price));
  }

  public static function import($file, $settings) {
    $i = 1;
//    $dbh = Dbi::getInstance();
    if (file_exists($file) && is_readable($file)) {
      if ($fh = fopen($file, "rb")) {
        $delimiter = $settings['delimiter'];
        $parentId = CategoriesModel::getIdByName($settings['category']);

//        if (!feof($fh)) {
//          fgets($fh);
//        }

//        self::init();
        switch ($parentId) {
          case 1:
            self::init();
            while (!feof($fh)/* && $i < 100*/) {
              ++$i;
              $row = explode($delimiter, fgets($fh));
              self::import1($row, $parentId);
            }
            break;
          case 2:
            self::init();
            while (!feof($fh)) {
              ++$i;
              $row = explode($delimiter, fgets($fh));
              self::importOther($row, $parentId, 1);
            }
            break;
          case 3:
            self::init();
            while (!feof($fh)) {
              ++$i;
              $row = explode($delimiter, fgets($fh));
              self::importOther($row, $parentId, 0);
            }
            break;
          }
      }
      else {
        echo "Ошибка при открытии файла!";
      }
      fclose($fh);
      echo '<p>Загружено ' . $i;
      echo ' Загрузка успешно завершена</p>';
    }
  }

  public static function addOrder() {
    $dbh = Dbi::getInstance();
//    $categories = $dbh->query('SELECT id FROM categories');
//    $categories = $categories->fetchAll();
    $categories = $dbh->select('id')->from('categories')->assoc();
    $updateCategories = $dbh->update('categories')->set(Array('`order`' => 0))->whereE('id', 0)->statement();

    print_r($categories);
    foreach ($categories as $index => $category) {
//      $dbh->queryData('UPDATE categories SET `order`=? WHERE id=?', [$index + 1, $category['id']]);
      $updateCategories->execute(Array($index + 1, $category['id']));
    }
//    for ($i = 0; $i < $categories[0]; ++$i) {
//      $dbh->queryData('INSERT INTO categories (`order`) VALUES (?)', [$i + 1]);
//    }
  }
}
