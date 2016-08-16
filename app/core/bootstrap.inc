<?php

require_once __DIR__ . '/System/helpers.php';
//require_once __DIR__ . '/System/Loader.php';

//use True\System\Loader;
use True\Support\Facades\App;
use True\Support\Facades\Config;
use True\Support\Facades\View;

//Loader::register('True', '/core');
//Loader::register('Logic', '/app/logic');

// Init facades
Config::load('/core/config.php');
App::init();
View::init(new \True\Services\View\Twig());
