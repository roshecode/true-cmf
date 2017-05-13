<a href="/">Home</a>
<a href="/account/roma">Account</a>
<form action="/delete" method="POST">
    <h2>Delete method</h2>
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit">Delete</button>
</form>
<form action="/put" method="POST">
    <h2>Put method</h2>
    <input type="hidden" name="_method" value="PUT">
    <button type="submit">Put</button>
</form>
<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

$start = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../libs/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

//\T\Facades\Route::make('GET', '/');
//die('EXIT');

//use T\Facades\Lang;

//echo Lang::get('debug.exceptions.InvalidArgument');
//Lang::load('ru-RU');
//echo Lang::get('debug.exceptions.InvalidArgument');

//print_r((microtime(true) - $start) * 1000 . 'ms');
