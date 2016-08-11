<?php

use True\Routing\Router;

define('APP_DIR', __DIR__ . '/app');
require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/core/bootstrap.inc';

use \True\View\Block;
use \True\IoC\Facades\App;

App::sayHello();

//$twig = new \True\View\Twig();
//$static_block = new Block('logo');
//$dynamic_block = new Block('article', Block::TYPE_DYNAMIC, 'list');
//$twig->renderBlock($static_block);
//$twig->renderBlock($dynamic_block, [
//    ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
//    ['title' => 'My second article', 'text' => 'I like what I doing.'],
//]);
//$twig->render('layouts/pages/home.twig');

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
