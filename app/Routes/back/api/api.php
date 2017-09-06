<?php

namespace App\Controllers;

use Core\Services\Facades\Route;

Route::api(function () {
    Route::get('users', [UsersController::class => 'all']);

    Route::get('users/:slug', [UsersController::class => 'withSlug']);

    Route::post('users/:slug', [UsersController::class => 'post']);

    Route::get('products', [ProductsController::class => 'get']);
});
