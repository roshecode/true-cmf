<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);

define('CUT'  , '/avtomagazin.dp.ua/');
//define('HOME' , 'http://work/avtomagazin.dp.ua'); // domain
define('HOME' , 'http://avto.ua'); // domain
define('TITLE', 'Интернет-магазин автозапчастей');

define('APP'        , getcwd());
define('LIBS'       , APP . '/core/libs');
define('SITE'       , APP . '/site');
define('THEME'      , SITE . '/theme');
define('DATA'       , SITE . '/data');
define('TEMPLATE'   , SITE . '/data/templates');

define('TPL_PARSER_CACHE', SITE . '/compilation_cache');
define('TPL_PARSER_AUTO_RELOAD', false);
