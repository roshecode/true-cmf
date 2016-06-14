<?php

namespace Route;

use Views\BaseView;

class Router {
  public static function init() {
//    $url = str_replace(CUT, '', strtok($_SERVER["REQUEST_URI"], '?'));
//    $items = explode('/', $url);
    $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
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
}