<?php

namespace App\Controllers;

use T\Facades\Route;
use T\Services\Response;

//Route::group('api', function () {
//    Route::get('users/:slug', function($params) {
//        $user = $this->box->make(\App\Models\User::class)->first(['slug' => $params[0]]);
//        $this->box->make(Response::class)->headers->set('Content-Type', 'application/json');
//        return $user ? json_encode($user) : "There are no users with slug $params[0]";
//    });
//});

Route::api(function () {
    Route::get('users', [Users::class => 'all']);

    Route::get('users/:slug', [Users::class => 'withSlug']);
});

$make = Route::make('GET', '/api/users');
if (is_array($make[0])) {
    $class = key($make[0]);
    $method = current($make[0]);
    $content = $this->box->make($class)->$method(...$make[1]);
} else {
    $content = call_user_func_array($make[0], $make[1]);
}

Route::delete('delete', function() {
    return 'DELETE method';
});

Route::put('put', function() {
    return 'PUT method';
});
