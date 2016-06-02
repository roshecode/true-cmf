<?php

function autoload($class)
{
  if (file_exists(__DIR__ . '/Classes/' . $class . '.php')) {
    require_once __DIR__ . '/Classes/' . $class . '.php';
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
