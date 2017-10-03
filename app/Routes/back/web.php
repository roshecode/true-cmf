<?php

use Core\Services\Facades\Route;
use Core\Services\Facades\View;

Route::get(':route:.*', function() {
    return View::render('default/main', ['title' => 'Home']);
});

Route::get('/admin', function() {
    return View::render('default/admin', ['title' => 'Admin']);
});

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
