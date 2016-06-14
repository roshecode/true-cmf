<?php

namespace External;

class Lib {
  public static function twig() {
    require_once LIB . '/Twig/Autoloader.php';
    \Twig_Autoloader::register();
    $loader = new \Twig_Loader_Filesystem(SITE . '/data/templates');
    $twig = new \Twig_Environment($loader, array(
      'debug' => true,
      'cache' => TPL_PARSER_CACHE,
      'auto_reload' => TPL_PARSER_AUTO_RELOAD
    ));
    $twig->addExtension(new \Twig_Extension_Debug());
    $filter = new \Twig_SimpleFilter('correctEnd', function($val, $zero, $one, $two) {
      echo \Tools\Functions::correctEnd($val, $zero, $one, $two);
    });
    $twig->addFilter($filter);
    return $twig;
  }
}