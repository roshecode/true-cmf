<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/bootstrap.inc';

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

View::render('layouts/pages/home', __DIR__ . '/app/core/Themes/default/structure.yml');

//Router::start();
//Router::get('home/page', function($data) {
//    echo $data;
//});
