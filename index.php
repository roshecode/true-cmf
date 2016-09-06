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
$box->bind('Twig_LoaderInterface', 'Twig_Loader_Filesystem');
$box->singleton('Truth\Support\Interfaces\ViewInterface', 'Truth\Services\View\Twig');
$View = $box->make('Truth\Support\Interfaces\ViewInterface', [
    APP_DIR . '/core/Themes',
    [
        'cache' => APP_DIR . '/cache/themes',
        'debug' => true,
        'auto_reload' => true
    ]
]);
echo $View->render('Layout 1');
echo $box->make('Truth\Support\Interfaces\ViewInterface')->render('Layout 2');
dd($box);



//use \Truth\View\Block;
//use \Truth\Support\Facades\View;
//
//$static_block = new Block('logo');
//$dynamic_block = new Block('article', Block::TYPE_DYNAMIC, 'list');
//View::renderBlock($static_block);
//View::renderBlock($dynamic_block, [
//    ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
//    ['title' => 'My second article', 'text' => 'I like what I doing.'],
//]);
//View::test();

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
