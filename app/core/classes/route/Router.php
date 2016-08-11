<?php

namespace Route;

use Tools\Functions;
use Views\BaseView;
use Views\FrontView;

class Router {
  private static $routes;

  public static function init() {
//    $url = str_replace(CUT, '', strtok($_SERVER["REQUEST_URI"], '?'));
//    $items = explode('/', $url);
    $explode = explode('?', $_SERVER['REQUEST_URI'], 2);
    $uri_parts = $explode[0];
    $uri_parts = explode('/', $uri_parts);
    array_shift($uri_parts);
    $itemsCount = count($uri_parts);

    $data = array_pop($uri_parts);
    $action = array_pop($uri_parts);
    $controller = $uri_parts ? $uri_parts[0] : 0;
    if (!$data) $data = 0;
    if ($itemsCount < 2) $action = $data;
    if (!$controller) $controller = 'front';
    if (!$action) $action = 'page';
    $controllerName = 'Controllers\\' . ucfirst($controller) . 'Controller';

//    try {
      $controller = new $controllerName();
      $actionMethod = strtolower($action);
      $controller->$actionMethod($data);
//    } catch (\Error $error) {
//      $baseView = new BaseView();
//      $baseView->display('error');
//    }
  }

  public static function get($url, $func) {
    self::$routes[$url] = $func;
  }

  public static function start() {
    $explode = explode('?', $_SERVER['REQUEST_URI'], 2);
    $uri_parts = $explode[0];
    $uri_parts = explode('/', $uri_parts);
    $uri_parts_count = count($uri_parts);
//    Functions::printr($uri_parts);

    array_shift($uri_parts);
    if ($uri_parts[0] == 'page' || $uri_parts[0] == 'form' || $uri_parts[0] == 'product' || $uri_parts[0] == 'search') {
      $controller = 'home';
      if (!empty($uri_parts[0])) {
        $action = array_shift($uri_parts);
      } else {
        $action = 'index';
      }
    } else {
      if (!empty($uri_parts[0])) {
        $controller = array_shift($uri_parts);
      } else {
        $controller = 'home';
      }
      if (!empty($uri_parts[0])) {
        $action = array_shift($uri_parts);
      } else {
        $action = 'index';
      }
    }
    $data = $uri_parts;

//    Functions::printr($controller);
//    Functions::printr($action);
//    Functions::printr($data);

    try {
      if (!$controller) $controller = 'front';
      $controllerName = 'Controllers\\' . ucfirst($controller) . 'Controller';
      if (class_exists($controllerName)) {
        $controller = new $controllerName();
        $actionMethod = strtolower($action);
        if (method_exists($controllerName, $actionMethod)) {
          $controller->$actionMethod($data);
        } else {
          $front = new FrontView();
          $front->error();
        }
      } else {
        $front = new FrontView();
        $front->error();
      }
    } catch (\Exception $error) {
      $front = new FrontView();
      $front->error();
    }
  }
}