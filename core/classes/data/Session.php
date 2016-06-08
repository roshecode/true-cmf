<?php

namespace Data;

class Session {
  const SESSION_STARTED = TRUE;
  const SESSION_NOT_STARTED = FALSE;

  // The state of the session
  private static $sessionState = self::SESSION_NOT_STARTED;

  // THE only instance of the class
  private static $instance;

  private function __construct($data) {
//    if ($data) {
      foreach ($data as $key => $value) {
        $_SESSION[$key] = $value;
      }
//    }
  }

  public static function employ($data = null) {
    if (!isset(self::$instance)) {
//      session_start();
      self::$instance = new self($data);
    }
    self::$instance->start();
    return self::$instance;
  }

  public static function start() {
    if (self::$sessionState == self::SESSION_NOT_STARTED) {
      self::$sessionState = session_start();
    }
    return self::$sessionState;
  }

  public static function put($name , $value) {
    $_SESSION[$name] = $value;
  }

  public static function add($name , $value) {
    $_SESSION[$name] += $value;
  }

  public static function get($name, $ifNotExistReturn = null) {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
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

  public static function destroy() {
    if (self::$sessionState == self::SESSION_STARTED) {
      self::$sessionState = !session_destroy();
      unset($_SESSION);
      return !self::$sessionState;
    }
    return false;
  }
}