<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../libs/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

//$client = new MongoDB\Client("mongodb://localhost:27017");
//$db = $client->rosem;
//$users = $db->users;
//$users->insertOne(['login' => 'rosem', 'pass']);
//foreach ($users->find() as $user)
//    d($user->name);

//for ($users->find() as $user) {
//    echo $user->name;
//}

//\T\Facades\Route::make('GET', '/');
//die('EXIT');
//Box::make(\T\Interfaces\Route::class)->get('GET', 'good');

//use T\Facades\Lang;

//echo Lang::get('debug.exceptions.InvalidArgument');
//Lang::load('ru-RU');
//echo Lang::get('debug.exceptions.InvalidArgument');

//print_r((microtime(true) - $start) * 1000 . 'ms');
