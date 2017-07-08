<?php
define('BASEDIR', __DIR__);

use T\Interfaces\KernelInterface;

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init dotenv
(new Dotenv\Dotenv(__DIR__))->load();

// init container and register services
$servicesConfigFile = __DIR__ . '/config/services.php';
if (file_exists($servicesConfigFile)) {
    $box = new \T\Services\Box();
    $box->pack(include $servicesConfigFile);
} else throw new Exception('Services configuration file not found');

// launch app
$kernel = $box[KernelInterface::class];
//$request = T\Services\Request::capture();
$request = $box->make(\Psr\Http\Message\ServerRequestInterface::class);
//$box->instance(\T\Interfaces\Request::class, $request);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

//$box->make(function () {});

//$server = Zend\Diactoros\Server::createServer(
//    new RequestHandler(),
//    $_SERVER,
//    $_GET,
//    $_POST,
//    $_COOKIE,
//    $_FILES
//);
//
//$server->listen(function ($request, $response, $error = null) {
//    // Final Handler
//    if (! $error) {
//        return $response;
//    }
//
//    // Handle extra errors etc here
//});
