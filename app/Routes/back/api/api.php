<?php

namespace App\Controllers;

use T\Facades\Route;
use T\Services\Response;

Route::api(function () {
    Route::get('users', [UsersController::class => 'all']);

    Route::get('users/:slug', [UsersController::class => 'withSlug']);

    Route::post('users/:slug', [UsersController::class => 'post']);
});

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
