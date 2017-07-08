<?php

use T\Facades\Route;

Route::get('::.*', function() {
    return $this->box[T\Services\View::class]->render('default/main', ['title' => 'Home']);
});

Route::get('/admin', function() {
    return $this->box[T\Services\View::class]->render('default/admin', ['title' => 'Admin']);
});

include __DIR__ . '/api/api.php';
