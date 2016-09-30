<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.inc';

$router = new \Truth\Support\Services\Routing\RegexRouter\RegexRouter();
print_r($router->match([], 'true/:str:[a-z-]+/:int', 'handler0'));
print_r($router->make('true/poka-rf/134'));
//$router->dispatch('1|true/2|privet/3|23');
// 'true/:str:[a-z-]+/name'
// 'true/:int:[\d]+'
// '@[^\s/]+/(?|([a-z-]+)|([\d]+))/[^\s/]+@i'
die;



use \Truth\Support\Facades\Box;
use \Truth\Support\Facades\Lang;
use \Truth\Support\Facades\Config;
use Truth\Support\Services\Routing\Router;
use \Truth\Support\Facades\View;

$str = 'true/\/how\//gfgfg\/gf/:rere g/grg+\/gffr\\/\/\gfgf/\/\\///gf/\//\\\//';
$i = 0;
preg_replace_callback('@([^/]*(?:\\\/(*SKIP))[^/]*)|[^/]+@i', function ($matches) use (&$str, &$i) {
    echo ++$i . ') ' . $matches[0] . '<br />';
//    $str = '1';
}, $str);
die;

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

//$start = microtime(true);
//$router = new \Truth\Support\Services\Routing\TrueRouter\Router('true');
//$router->match([], '/:site|c/users/:user|^c+s0$/:name:\w{3}', function() {});
//$router->match([], '/:site/users/:user|s/profile', function() {});
//$router->match([], '/:site/blog', function() {});
//$router->match([], '/', function() {});
//print_r((microtime(true) - $start) * 1000 . 'ms');
//die;

//$start = microtime(true);
//$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
//    $r->addRoute('GET', '/', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
////    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
//});
//print_r((microtime(true) - $start) * 1000 . 'ms');
//die;

//class MyController
//{
//    public static function index($name, $age) {
//        echo 'INDEX: ' . 'My name is ' . $name . '. I ' . $age . ' years old.';
//    }
//}

$start = microtime(true);
//$router = new Router('true');
//$router = new Truth\Support\Services\Routing\ArrayRouter\Router('true');
$router = new \Truth\Support\Services\Routing\TrueRouter\Router('true');
//for ($i = 0; $i < 1000; ++$i) {
    $router->match('GET', '/', function () {
        echo 'hello';
    });
    $router->match('GET', '/:name|cs/:age|i', function ($name, $surname) {
        echo 'My name is ' . $name . '. I ' . $surname . ' years old.';
    });
//    $router->match('GET', '/:name|cs/:age|i', 'MyController@index');
//}
//$router->make('true/');
//$router->cache();
print_r((microtime(true) - $start) * 1000 . 'ms');
die;
//View::render('layouts/pages/home');
//dd(Box::getInstance());

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
