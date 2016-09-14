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
$box = new \Truth\Support\Services\Locator\Box();
//$box->pack(COREDIR . 'Configuration', ['main.php', 'services.php', 'settings.php']);
$box->pack(COREDIR . 'pack.php');
