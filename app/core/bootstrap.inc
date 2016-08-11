<?php

require_once __DIR__ . '/System/helpers.php';
//require_once __DIR__ . '/System/Loader.php';

//use True\System\Loader;
use True\Facades\IoC\App;
use True\Facades\System\Config;

//Loader::register('True', '/core');
//Loader::register('Logic', '/app/logic');

// Init facades
App::init();
//View::init();
Config::load('/core/config.php');
