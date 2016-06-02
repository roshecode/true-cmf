<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

define('PATH', 'http://work/avtomagazin.dp.ua'); // domain
define('MODEL', 'model/');
define('CONTROLLER', 'controller/');
define('VIEW', 'view/');
define('TEMPLATE', 'shop'); // active template
define('TITLE', 'Интернет-магазин автозапчастей');

return array(
  'DB_DRIVER'   => 'mysql',
  'DB_HOST'     => 'localhost',
  'DB_NAME'     => 'avtomagazin',
  'DB_USER'     => 'root',
  'DB_PASSWORD' => '71868716'
);
