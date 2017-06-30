<?php

namespace App\Controllers;

use T\Facades\Route;
use T\Services\Response;

Route::api(function () {
    Route::get('users', [Users::class => 'all']);

    Route::get('users/:slug', [Users::class => 'withSlug']);

    Route::post('users/:slug', [Users::class => 'post']);
});

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
