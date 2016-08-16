<?php

use True\Routing\Router;

define('APP_DIR', __DIR__ . '/app');
require_once __DIR__ . '/app/vendor/autoload.php';
//require_once __DIR__ . '/app/core/bootstrap.inc';
require_once __DIR__ . '/app/core/System/helpers.php';

$config = new \True\Data\FileSystem\FileArrayQuery(__DIR__ . '/app/core/config.php');
//var_dump($config);
dd($config->get('directories.cache.themes'));

//use \True\View\Block;
//use \True\Support\Facades\View;
//
//$static_block = new Block('logo');
//$dynamic_block = new Block('article', Block::TYPE_DYNAMIC, 'list');
//View::renderBlock($static_block);
//View::renderBlock($dynamic_block, [
//    ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
//    ['title' => 'My second article', 'text' => 'I like what I doing.'],
//]);
//View::test();
//
////Router::start();
////Router::get('home/page', function($data) {
////    echo $data;
////});
