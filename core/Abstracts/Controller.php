<?php

namespace Core\Abstracts;

use Core\Services\Contracts\DB;
use Core\Services\Route;

abstract class Controller {
//    /**
//     * @var DBInterface
//     */
//    protected $db;
//
//    public function __construct(DBInterface $db)
//    {
//        $this->db = $db;
//    }

    public function index() {
        echo '404';
    }

//    public function __call($name, $arguments) {
//        Route::move(ucfirst($name).'Controller');
//    }
}
