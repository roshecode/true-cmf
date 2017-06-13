<?php

namespace App\Controllers;

use T\Facades\Box;
use T\Facades\Route;
use T\Services\Response;

//$route = \T\Facades\Box::make(\T\Interfaces\Route::class);

//Route::get('api/users/:slug', function($params) {
//
//});

Route::get('api/users/:slug', function($params) {
    $user = $this->box->make(\App\Models\User::class)->first(['slug' => $params[0]]);
    $this->box->make(Response::class)->headers->set('Content-Type', 'application/json');
    return $user ? json_encode($user) : "There are no users with slug $params[0]";
});

Route::get('api/articles', Articles::class, 'show');
Route::get('api/articles/:slug', Articles::class, 'showOne');

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
