<?php

namespace App\Controllers;

use T\Abstracts\Controller;

class Articles extends Controller {
    public function getAll() {
        return 'Get all articles';
    }

    public function getBySlug($slug) {
        return "Get by $slug";
    }
}
