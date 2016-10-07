<?php

require_once __DIR__ . '/core/System/helpers.php';
define('BASEDIR', __DIR__ . '/');
define('COREDIR', BASEDIR . 'core/');
define('CORE_SERVICES', 'Truth\\Support\\Services\\');

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container and register
$box = new \T\Support\Services\Locator\Box();
$box->pack(BASEDIR . 'configuration/services.php');
