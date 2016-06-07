<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

define('DOMAIN', 'avtomagazin.dp.ua');
define('HOME', 'http://work/avtomagazin.dp.ua'); // domain
define('TITLE', 'Интернет-магазин автозапчастей');
define('DATA', HOME . '/site/data');

define('MODEL'      , __DIR__ . '/models');
define('CONTROLLER' , __DIR__ . '/controllers');
define('VIEW'       , __DIR__ . '/views');
define('SITE'       , getcwd() . '/site'); // active template
define('THEME'      , SITE . '/theme'); // active template
define('TEMPLATE'   , SITE . '/data/templates'); // active template
//define('TEMPLATE', __DIR__ . '/../site/theme/default/templates/'); // active template
