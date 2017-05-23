<?php

namespace App\Controllers;

use T\Abstracts\Controller;

class Articles extends Controller {
    public function showAll() {
        return 'return ArticlesRepository->getAll()';
    }

    public function showOne($slug) {
        return "return ArticlesRepository->getBySlug($slug)";
    }
}
