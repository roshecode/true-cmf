<?php

use Truth\Routing\Router;

define('APP_DIR', (DIRECTORY_SEPARATOR == "\\" ? str_replace('\\', '/', __DIR__) : __DIR__) . '/app');
require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/core/System/helpers.php';
//require_once __DIR__ . '/app/core/bootstrap.inc';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

require_once __DIR__ . '/app/core/Data/FileSystem/FS.php';

$ed = new \Truth\Exceptions\Manage\ExceptionDispatcher();
$ed->register(new \Truth\Exceptions\Manage\ExceptionHandler());

use \Truth\Exceptions\Manage\Envisage;
Envisage::register();
/**
 * @param array $arr
 */
function test(array $arr) {
//    $some = $arr[2];
    Envisage::isArray($arr);
    print_r($arr);
} ?><?php
test(new StdClass, 'fg');
die;

Truth\Data\FileSystem\FS::insert('gfg');
die;

$app = new \Truth\IoC\LaravelContainer();

$app->bind('SomeInterface', 'Something');
///** @var True\UserRepository $test */$test = $app->make('UserRepository');
$test = $app->make('UserRepository');
$test->
$test = new \Truth\IoC\Box();
$test->test('\\True\\Services\\View\\Twig');
die;

use \Truth\View\Block;
use \Truth\Support\Facades\View;

$static_block = new Block('logo');
$dynamic_block = new Block('article', Block::TYPE_DYNAMIC, 'list');
View::renderBlock($static_block);
View::renderBlock($dynamic_block, [
    ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
    ['title' => 'My second article', 'text' => 'I like what I doing.'],
]);
View::test();

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
