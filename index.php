<?php
require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/bootstrap.inc';

require_once CONTROLLER . '/frontController.php';

use data\DB;

$db = DB::getInstance();

echo 'INDEX' . PHP_EOL;


