<?php

use T\Facades\Route;

Route::get('/', function() {
    return 'You are at home';
});
Route::get('/test/:user', function($params) {
//    dd($params);
    return 'Good test';
});
Route::delete('delete', function() {
    return 'DELETE method';
});
Route::put('put', function() {
    return 'PUT method';
});
