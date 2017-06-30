<?php
define('BASEDIR', __DIR__);

use T\Interfaces\KernelInterface;

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container and register services
$servicesConfigFile = __DIR__ . '/config/services.php';
if (file_exists($servicesConfigFile)) {
    $box = new \T\Services\Box();
    $box->pack(include $servicesConfigFile);
} else throw new Exception('Services configuration file not found');

// launch app
$kernel = $box[KernelInterface::class];
//$request = T\Services\Request::capture();
$request = $box->make(\T\Interfaces\RequestInterface::class);
//$box->instance(\T\Interfaces\Request::class, $request);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
