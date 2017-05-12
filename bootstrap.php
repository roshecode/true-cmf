<?php
define('BASEDIR', __DIR__);

use T\Exceptions\FileNotFoundException;

require_once __DIR__ . '/helpers.php';

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container and register services
$servicesConfigFile = __DIR__ . '/config/services.php';
if (file_exists($servicesConfigFile)) {
    $box = new \T\Services\Container\Box();
    $box->pack(include $servicesConfigFile);
//    d($box->make('Route'));
} else throw new FileNotFoundException('File to pack not found');

// launch app
$kernel = $box['Kernel'];
$response = $kernel->handle(
    $request = T\Services\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
