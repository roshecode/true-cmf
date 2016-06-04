<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

define('HOME', 'http://work/avtomagazin.dp.ua'); // domain
define('TITLE', 'Интернет-магазин автозапчастей');

define('MODEL'      , __DIR__ . '/models');
define('CONTROLLER' , __DIR__ . '/controllers');
define('VIEW'       , __DIR__ . '/views');
//define('TEMPLATE'   , __DIR__ . '/views/layout'); // active template
define('SITE'   , __DIR__ . '/../site'); // active template
define('THEME'   , __DIR__ . '/../site/theme'); // active template
