<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/bootstrap.inc';

use \Truth\Support\Facades\Box;
use \Truth\Support\Facades\Lang;
use \Truth\Support\Facades\Config;
use Truth\Support\Services\Routing\Router;
use \Truth\Support\Facades\View;

//$single = true;
//Box::bind('hi', function($firstName, $lastName) {
//    return 'Hello, ' . $firstName . ' ' . $lastName . '!';
//}, $single, true);
//echo Box::make('hi', ['Roman', 'Shevchenko']);
//echo Box::make('hi');
//echo Box::make('hi', ['Andrii', 'Zholob']);
//echo Box::make('hi');
//echo Box::isShared('hi');
//echo $single;
//die;

//$test = \Truth\Support\Facades\FS::take('core/Configuration/services.ini');
//dd(parse_ini_file(COREDIR . 'Configuration/settings.ini', true));
//Box::make();

$start = microtime(true);
$router = new \Truth\Support\Services\Routing\TrueRouter\Router('true');
$router->match([], '/:site|c/users/:user|^c+s0$/:name:\w{3}', function() {});
$router->match([], '/:site/users/:user|s/profile', function() {});
$router->match([], '/:site/blog', function() {});
$router->match([], '/', function() {});
print_r((microtime(true) - $start) * 1000 . 'ms');
die;

$start = microtime(true);
for ($i = 0; $i < 200; ++$i) {
    $router = new Router('true');
//$router = new Truth\Support\Services\Routing\ArrayRouter\Router('true');
    $router->match('GET', '/', function () {
        echo 'hello';
    });
    $router->match('GET', '/:name|letters/:age|uint', function ($name, $surname) {
        echo 'My name is ' . $name . '. I ' . $surname . ' years old.';
    });
}
//$router->make('true/');
print_r((microtime(true) - $start) * 1000 . 'ms');
die;
//View::render('layouts/pages/home');
//dd(Box::getInstance());

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
