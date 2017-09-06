<?php

namespace App\Models;

use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

class Product
{
    use ProductValidator;

    protected $repositoryClass = ProductRepository::class;

    public $id, $name, $image_src, $price, $currency, $rating;

    protected function getValue($name)
    {
        return $this->$name;
    }
}
