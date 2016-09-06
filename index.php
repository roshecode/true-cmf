<?php

use Truth\Routing\Router;

define('APP_DIR', (DIRECTORY_SEPARATOR == "\\" ? str_replace('\\', '/', __DIR__) : __DIR__) . '/app');
require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/core/System/helpers.php';
//require_once __DIR__ . '/app/core/bootstrap.inc';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

$box = new \Truth\IoC\Box();

//$box->bind('hi', function($firstName, $lastName) {
//    return 'Hello, ' . $firstName . ' ' . $lastName . '!';
//});
//echo $box->make('hi', ['Roman', 'Shevchenko']);
//echo $box->make('hi', ['Andrii', 'Zholob']);
//die;

use \Truth\Support\Facades\View;
use \Truth\View\Block;
\Truth\Services\View\Twig::register($box);
//echo View::render('Layout 1');
//echo View::render('Layout 2');
//dd($box);

$static_block = new Block('logo');
$dynamic_block = new Block('article', Block::TYPE_DYNAMIC, 'list');
View::renderBlock($static_block);
View::renderBlock($dynamic_block, [
    ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
    ['title' => 'My second article', 'text' => 'I like what I doing.'],
]);

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
