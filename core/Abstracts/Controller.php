<?php

namespace T\Abstracts;

use T\Services\Routing\Route;

abstract class Controller {
    public function index() {
        echo '404';
    }

    public function __call($name, $arguments) {
        Route::move(ucfirst($name).'Controller');
    }
}
