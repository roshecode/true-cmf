<?php

use T\Facades\Route;

Route::get('/', function() {
    return 'Yeap';
});
Route::get('/test', function() {
    return 'Good test';
});
Route::delete('delete', function() {
    return 'and DELETE method';
});
Route::put('put', function() {
    return 'PUT method';
});
