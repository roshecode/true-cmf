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

  public function lastInsertId($name = null) {
    return intval($this->_dbh->lastInsertId($name));
  }

  public function prepare($query) {
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
    return $this->_dbh->query($query)->fetchAll(PDO::FETCH_ASSOC);
//    return $this->_dbh->query($query, PDO::FETCH_CLASS, get_called_class());
  }
  public function queryMapData($query, $data)
  {
//    return $this->queryData($query, $data)->fetchAll(PDO::FETCH_CLASS, get_called_class());
    return $this->queryData($query, $data)->fetchAll(PDO::FETCH_ASSOC);
  }

  private function str_insert($table, &$data) {
    return
      'INSERT INTO ' . $table . '(' . implode(',', array_keys($data)) . ')' .
      ' VALUES (?' . str_repeat(',?', count($data) - 1) . ')';
  }

  private function str_leftJoin($select, $leftTable, $rightTable, $matches) {
    return 'SELECT ' . implode(',', $select) . ' FROM ' . $leftTable . ' LEFT JOIN ' . $rightTable .
    ' ON ' . $leftTable . '.' . array_keys($matches)[0] . '=' . $rightTable . '.' . array_values($matches)[0];
  }

  public function insert($table, $data) {
    $this->prepare($this->str_insert($table, $data));
    return self::$_instance->execute(array_values($data));
  }

  public function select($what, $table, $data) {
    $this->_stmt = $this->_dbh->prepare('SELECT ' . implode(',', $what) . ' FROM ' . $table .
      ' WHERE ' . array_keys($data)[0] . '=?');
    $this->_stmt->execute(array_values($data));
    return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
//    return $this->_stmt->fetchAll(PDO::FETCH_BOUND);
  }

  public function selectAll($table) {
    return $this->queryMap('SELECT * FROM ' . $table);
  }

  public function leftJoin($select, $leftTable, $rightTable, $matches, $condition = 'AND') {
    return $this->queryMap($this->str_leftJoin($select, $leftTable, $rightTable, $matches));
  }

  public function leftJoinCond($select, $leftTable, $rightTable, $matches, $cond) {
    return $this->queryMapData($this->str_leftJoin($select, $leftTable, $rightTable, $matches) .
      ' WHERE ' . array_keys($cond)[0] . '=?', array_values($cond));
  }

//  public selectRange($what, $table, $)

  public function update($table, $data) {
    $data = array_reverse($data);
    $dataKeys = array_keys($data);
    $matchValue = array_pop($data);
    $matchName = $dataKeys[count($dataKeys) - 1];
    array_pop($dataKeys);
    $this->_stmt = $this->_dbh->prepare('UPDATE ' . $table .
      ' SET ' . implode('=?,', $dataKeys) . '=? WHERE ' . $matchName . '=?');
    $data[$matchName] = $matchValue;
//    print_r($dataKeys);
//    print_r($data);
    return $this->_stmt->execute(array_values($data));
  }
  
  public function save($table, $data, $duplicate = ['id' => 'id']) {
    return $this->queryData($this->str_insert($table, $data) .
      ' ON DUPLICATE KEY UPDATE ' .
      array_keys($duplicate)[0] . '=' . array_values($duplicate)[0], array_values($data));
  }

  public function getIdOfName($table, $match) {
    return self::select(['id'], $table, ['name' => $match])[0]['id'];
  }
}
