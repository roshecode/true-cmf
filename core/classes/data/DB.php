<?php

namespace Data;
use PDO;
use PDOException;
use PDOStatement;

class DB {
  private $_dbh;
  private $_stmt;
  /**
   * @var DB|PDOStatement
   */
  private static $_instance = null;

  private function __construct()
  {
    try {
      $p = require __DIR__ . '/config.php';
      $this->_dbh = new PDO(
        $p['DB_DRIVER'] . ':host=' .
        $p['DB_HOST'] . ';dbname=' .
        $p['DB_NAME'],
        $p['DB_USER'],
        $p['DB_PASSWORD'],
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
      $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'FAILED TO GET DB HANDLE: >' . $e->getMessage();
    }
  }

  private function __clone() {}
  
  public function __call($name, $arguments)
  {
    return call_user_func_array(array($this->_stmt, $name), $arguments);
  }

  /**
   * @return DB|PDOStatement|null
   */
  public static function getInstance()
  {
    if (self::$_instance == null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  public function prepare($query)
  {
    $this->_stmt = $this->_dbh->prepare($query);
  }

  public function query($query) {
    return $this->_dbh->query($query);
  }

  public function queryData($query, $data)
  {
    $stmt = $this->_dbh->prepare($query);
    $stmt->execute($data);
    return $stmt;
  }

  public function queryMap($query)
  {
//    return $this->_dbh->query($query)->fetchAll(PDO::FETCH_CLASS, get_called_class());
    return $this->_dbh->query($query)->fetchAll(PDO::FETCH_CLASS, get_called_class());
//    return $this->_dbh->query($query, PDO::FETCH_CLASS, get_called_class());
  }
  public function queryMapData($query, $data)
  {
    return $this->queryData($query, $data)->fetchAll(PDO::FETCH_CLASS, get_called_class());
  }

  public function insert($table, $data) {
//    $this->_stmt = $this->_dbh->prepare('INSERT INTO ' . $table .
//      '(' . implode(',', array_keys($data)) . ')' .
//      ' VALUES (?' . str_repeat(',?', count($data) - 1) . ')');
//    return $this->_stmt->execute(array_values($data));

    $this->prepare('INSERT INTO ' . $table .
      '(' . implode(',', array_keys($data)) . ')' .
      ' VALUES (?' . str_repeat(',?', count($data) - 1) . ')');
//    print_r(array_values($data));
//    return $this->execute(array_values($data));
//    return self::$_instance->execute(array_values($data));
    return self::$_instance->execute(array_values($data));
  }

  public function import($file, $settings) {
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

          try {
            $this->insert('categories', [
              'id' => ++$categoryIndex,
              'name' => preg_split('/(\s[a-zA-Z])|([,;:])/', $row[3])[0]
            ]);
          } catch (PDOException $e) {
            --$categoryIndex;
          }
          try {
            $this->insert('vendor', [
              'id' => ++$vendorIndex,
              'name' => $row[1]
            ]);
          } catch (PDOException $e) {
            --$vendorIndex;
          }
          $this->insert('products', [
            'code' => $row[0],
            'vendor_id' => $vendorIndex,
            'category_id' => $categoryIndex,
            'name' => $row[2],
            'description' => $row[3],
            'price' => intval($row[4])
          ]);
        }
      }
      else echo "Ошибка при открытии файла!";
      fclose($fh);
    }
  }
}
