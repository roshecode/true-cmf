<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

define('APP_DIR', (DIRECTORY_SEPARATOR == "\\" ? str_replace('\\', '/', __DIR__) : __DIR__) . '/app');
require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/core/System/helpers.php';
require_once __DIR__ . '/app/core/bootstrap.inc';

use \Truth\Support\Facades\Box;
use \Truth\Support\Facades\Lang;
use \Truth\Support\Facades\Config;
use Truth\Routing\Router;
use \Truth\Support\Facades\View;
use \Truth\View\Block;

//Box::bind('hi', function($firstName, $lastName) {
//    return 'Hello, ' . $firstName . ' ' . $lastName . '!';
//});
//echo Box::make('hi', ['Roman', 'Shevchenko']);
//echo Box::make('hi', ['Andrii', 'Zholob']);

//echo View::render('Layout 1');
//echo View::render('Layout 2');
//dd(Box::getInstance());

View::renderBlock(new Block('logo'));
View::renderBlock(new Block('article', Block::TYPE_DYNAMIC, 'list'), [
    ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
    ['title' => 'My second article', 'text' => 'I like what I doing.'],
]);

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
