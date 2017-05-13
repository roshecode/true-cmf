<?php

namespace App\Controllers;

use T\Facades\Route;

Route::get('/', function() {
    return 'You are at home';
});

Route::get('a/:arg', function($params) {
    echo 'Test good ' . $params[0];
});

Route::get('/current', Account\User::class, 'show');

//Route::get('/account/:user:\d+[/un[ne]cessary]', function($params) {
Route::get('/account/:user', function($params) {
    var_dump($params);
    return "Hello, $params[0]";
});

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
