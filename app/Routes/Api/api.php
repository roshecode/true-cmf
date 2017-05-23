<?php

namespace App\Controllers;

use T\Facades\Route;

//$route = \T\Facades\Box::make(\T\Interfaces\Route::class);

Route::get('/', function() {
    return 'Here we will return latest activity';
});

Route::get('user/:slug', function($params) {
    echo 'Hello, ' . $params[0] . '! How are you?';
});

Route::get('/articles', Articles::class, 'show');
Route::get('/articles/:slug', Articles::class, 'showOne');

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
