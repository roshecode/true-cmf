<?php

namespace Data;
use PDO;
use PDOException;
use PDOStatement;
use Tools\Functions;

class Dbi {
//  private static $dbh;
  public static $dbh;
  private static $stmt;
  private static $data;
//  private static $query;
  public static $query;
  private static $leftTable;
  private static $rightTable;
  /**
   * @var Dbi
   */
  private static $_instance = NULL;

  private function __construct() {
    try {
      define('DB_ACCESS', 1);
      $cfg = require __DIR__ . '/config.php';
      self::$dbh = new PDO(
        $cfg['DB_DRIVER'] . ':host=' .
        $cfg['DB_HOST'] . ';dbname=' .
        $cfg['DB_NAME'],
        $cfg['DB_USER'],
        $cfg['DB_PASSWORD'],
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
      self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'FAILED TO GET DB HANDLE: >' . $e->getMessage();
    }
  }

  private function __clone() {}

  public static function getInstance()
  {
    if (self::$_instance == null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  public static function select($data) {
//    self::$query = is_array($data) ? 'SELECT `' . implode('`,`', $data) . '`' : 'SELECT `' . $data . '`';
    self::$query = is_array($data) ? 'SELECT ' . implode(',', $data) : 'SELECT ' . $data;
    return self::$_instance;
  }

  public static function update($table) {
    self::$query = 'UPDATE ' . $table;
    return self::$_instance;
  }

  public static function delete() {
    self::$query = 'DELETE';
    return self::$_instance;
  }

  public static function truncate($table) {
    return self::$dbh->query('TRUNCATE TABLE ' . $table);
  }
  
  public static function from($table) {
    self::$leftTable = $table;
    self::$query .= ' FROM ' . $table;
    return self::$_instance;
  }
  
  public static function leftJoin($table) {
    self::$query .= ' LEFT OUTER JOIN ' . $table;
    self::$rightTable = $table;
    return self::$_instance;
  }
  
  public static function on($field1, $field2) {
    self::$query .= ' ON ' . self::$leftTable . '.' . $field1 . '=' . self::$rightTable . '.' . $field2;
    return self::$_instance;
  }

  public static function using($field) {
    self::$query .= ' USING `' . $field . '`';
    return self::$_instance;
  }

  public static function where($field, $value) {
//    self::$query .= ' WHERE `' . $field . '`=?';
    self::$query .= ' WHERE ' . $field . '=?';
//    self::$data[] = $value;
    self::$data = [$value];
    return self::$_instance;
  }

  public static function insertInto($table, $fields) {
//    self::$query = 'INSERT INTO ' . $table . '(`' . implode('`,`', $fields) . '`)';
    self::$query = 'INSERT INTO ' . $table . '(' . implode(',', $fields) . ')';
//    self::$data = $fields;
    return self::$_instance;
  }

  public static function values($data) {
    self::$query .= ' VALUES (?' . str_repeat(',?', count($data) - 1) . ')';
    self::$data = $data;
    return self::$_instance;
  }

  public static function set($data) {
//    self::$query .= ' SET `' . implode('`=?,', array_keys($data)) . '`';
    self::$query .= ' SET ' . implode('=?,', array_keys($data));
    self::$data = array_values($data);
    return self::$_instance;
  }

  public static function orderBy($order) {
    self::$query .= ' ORDER BY ' . $order;
    return self::$_instance;
  }

  public static function onDuplicateKeyUpdate($field1, $field2) {
//    self::$data[] = $value;
//    self::$query .= ' ON DUPLICATE KEY UPDATE `' . $field1 . '`=`' . $field2 . '`';
    self::$query .= ' ON DUPLICATE KEY UPDATE ' . $field1 . '=' . $field2;
    return self::$_instance;
  }

  public static function assoc() {
    if (self::$data) {
      self::$stmt = self::$dbh->prepare(self::$query);
      self::$stmt->execute(self::$data);
      return self::$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return self::$dbh->query(self::$query)->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function run($type = null) {
//    self::$stmt = self::$dbh->prepare(self::$query);
//    self::$stmt->execute(self::$data);
//    echo self::$query;
//    Functions::printr(self::$stmt->fetchAll(PDO::FETCH_ASSOC));
//    Functions::printr(self::$data);

    if (self::$data) {
      self::$stmt = self::$dbh->prepare(self::$query);
      $state = self::$stmt->execute(self::$data);
//      self::$data = [];
      return $type ? self::$stmt->fetchAll($type) : $state;
    }
    return $type ? self::$dbh->query(self::$query) : self::$dbh->query(self::$query)->fetchAll($type);
  }

  public static function getIdOfName($table, $id, $name) {
    return self::select($name)->from($table)->where('id', $id)->assoc()[0]['id'];
  }

  public static function getNameOfId($table, $name, $id) {
    return self::select($id)->from($table)->where('name', $name)->assoc()[0]['name'];
  }
}