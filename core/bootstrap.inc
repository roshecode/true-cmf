<?php

function autoload($class)
{
//  $file = __DIR__ . '/classes/' . str_replace('\\', '/', strtolower($class)) . '.php';
  $file = __DIR__ . '/classes/' . str_replace('\\', '/', lcfirst($class)) . '.php';
  echo $file;
  if (file_exists($file)) {
    require_once $file;
  }
  else {
    $classParts = explode('\\', $class);
//        $classParts[] = ucfirst(array_pop($classParts));
    $classParts[0] = __DIR__;
    $path = implode(DIRECTORY_SEPARATOR, $classParts) . '.php';
    if (file_exists($path)) {
      require_once $path;
    }
  }
}

spl_autoload_register('autoload');
