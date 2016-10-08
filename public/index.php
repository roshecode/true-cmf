<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

$start = microtime(true);

require_once __DIR__ . '/../app/vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.inc';

use \T\Support\Facades\Box;
use \T\Support\Facades\Lang;
use \T\Support\Facades\Config;
use T\Support\Facades\Route;
use \T\Support\Facades\View;

//Route::get('true/hi/bye', function() {
//    echo 'Bye, world';
//});
Route::get('true/hi', function() {
    echo 'Hello, world';
});

print_r((microtime(true) - $start) * 1000 . 'ms');
