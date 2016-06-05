<?php

function autoload($class)
{
//  $file = __DIR__ . '/classes/' . str_replace('\\', '/', strtolower($class)) . '.php';
  $file = __DIR__ . '/classes/' . lcfirst(str_replace('\\', '/', $class)) . '.php';
  if (file_exists($file)) {
    require_once $file;
  }
  else {
    $file = __DIR__ . '/../site/' . /*lcfirst*/(str_replace('\\', '/', $class)) . '.php';
    if (file_exists($file)) {
      require_once $file;
    }
  }
}

spl_autoload_register('autoload');
