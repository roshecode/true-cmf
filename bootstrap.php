<?php

require_once __DIR__ . '/helpers.php';
define('BASEDIR', __DIR__ . '/');

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// init container and register services
$box = new \T\Services\Container\Box();
$box->pack(__DIR__ . '/config/services.php');

// launch app
$kernel = $box['Kernel'];
$response = $kernel->handle(
    $request = T\Services\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
