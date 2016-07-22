<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 21.07.2016
 * Time: 13:28
 */

namespace True\Controllers;

use True\Routing\Router;

abstract class BaseController {
    public function index() {
        echo '404';
    }

    public function __call($name, $arguments) {
        Router::move(ucfirst($name).'Controller');
    }
}
