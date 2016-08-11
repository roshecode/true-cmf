<?php

namespace Data;

use Tools\Functions;

class Session {
  const SESSION_STARTED = TRUE;
  const SESSION_NOT_STARTED = FALSE;

  private static $initData;

  // The state of the session
  private static $sessionState = self::SESSION_NOT_STARTED;

  // THE only instance of the class
  private static $instance;

  private function __construct() {}

  public static function init($data = null) {
    self::$initData = $data;
    if (!isset(self::$instance)) {
//      session_start();
      self::$instance = new self();
    }
    self::$instance->start($data);
    return self::$instance;
  }

  public static function start($data = null) {
    if (self::$sessionState == self::SESSION_NOT_STARTED) {
      self::$sessionState = session_start();
//      Functions::printr($_SESSION);
//      if (!isset($_SESSION)) {
      if (empty($_SESSION)) {
        $_SESSION['_init'] = $data;
        foreach ($data as $key => $value) {
          $_SESSION[$key] = $value;
        }
      }
    }
    return self::$sessionState;
  }

  public static function put($name , $value) {
    $_SESSION[$name] = $value;
  }

  public static function add($name , $value) {
//    return $_SESSION[$name] += $value;
    return $_SESSION[$name] = round($_SESSION[$name] + $value, 2);
//    return $_SESSION[$name] = gmp_add($_SESSION[$name], $value);
  }

  public static function sub($name , $value) {
//    return $_SESSION[$name] -= $value;
    return $_SESSION[$name] = round($_SESSION[$name] - $value, 2);
//    return $_SESSION[$name] = gmp_sub($_SESSION[$name], $value);
  }

  public static function get($name, $ifNotExistReturn = null) {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $ifNotExistReturn;
  }

  public static function getAll($ifNotExistReturn = null) {
    if (isset($_SESSION)) {
      return $_SESSION;
    }
    return $ifNotExistReturn;
  }

  public static function &getReference($name, $ifNotExistReturn = null) {
//    return self::get($name, $ifNotExist);
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $ifNotExistReturn;
  }

  public static function has($name) {
    return isset($_SESSION[$name]);
  }

  public static function forget($name) {
    unset($_SESSION[$name]);
  }

  public static function countValues($name) {
//    return count($_SESSION[$name]);
    $count = 0;
    foreach ($_SESSION[$name] as $item) {
      $count += $item;
    }
    return $count;
  }

  public static function clear() {
//    $_SESSION = $_SESSION['_init'];
//    $_SESSION['_init'] = $_SESSION;
    return $_SESSION = self::$initData;
  }

  public static function destroy() {
    if (self::$sessionState == self::SESSION_STARTED) {
      self::$sessionState = !session_destroy();
      unset($_SESSION);
      return !self::$sessionState;
    }
    return false;
  }
}