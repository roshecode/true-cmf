<?php

require_once __DIR__ . '/helpers.php';
define('BASEDIR', __DIR__ . '/');
define('COREDIR', BASEDIR . 'core/');

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container and register
$box = new \T\Services\Container\Box();
$box->pack(BASEDIR . 'config/services.php');
