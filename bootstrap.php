<?php
define('BASEDIR', __DIR__);

use T\Interfaces\Kernel;

require_once __DIR__ . '/helpers.php';

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container and register services
$servicesConfigFile = __DIR__ . '/config/services.php';
if (file_exists($servicesConfigFile)) {
    $box = new \T\Services\Box();
    $box->pack(include $servicesConfigFile);

    $user = $box->make(App\Models\User::class);
    var_dump($user->first()->name);

//    $box->make(\T\Interfaces\Route::class)->
//    $box[\T\Interfaces\Route::class]->
//    \T\Facades\Box::
} else throw new Exception('File to pack not found');

// launch app
$kernel = $box[Kernel::class];
$response = $kernel->handle(
    $request = T\Services\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
