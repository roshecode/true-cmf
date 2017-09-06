<?php

namespace App\Controllers;

use Core\Abstracts\Controller;
use Core\Services\Contracts\DB;

class ProductsController extends Controller
{
    public function get()
    {
        $products = [
            [
                'id'         => 1,
                'name'       => 'T-shirt cool',
                'imageSrc'   => '/resources/images/product1.png',
                'price'      => 499,
                'rating'     => 4,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 2,
                'name'       => 'Dress',
                'imageSrc'   => '/resources/images/product2.png',
                'price'      => 605,
                'rating'     => 3,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 3,
                'name'       => 'Spinner',
                'imageSrc'   => '/resources/images/product3.png',
                'price'      => 99,
                'rating'     => 2,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 4,
                'name'       => 'Plants',
                'imageSrc'   => '/resources/images/product4.png',
                'price'      => 909,
                'rating'     => 1,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 5,
                'name'       => 'Bear',
                'imageSrc'   => '/resources/images/product5.png',
                'price'      => 1499.50,
                'rating'     => 4,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 6,
                'name'       => 'Big wide hat',
                'imageSrc'   => '/resources/images/product6.png',
                'price'      => 300.15,
                'rating'     => 4,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 7,
                'name'       => 'Shoes small foot',
                'imageSrc'   => '/resources/images/product7.png',
                'price'      => 19000,
                'rating'     => 2,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
            [
                'id'         => 8,
                'name'       => 'Vodka',
                'imageSrc'   => '/resources/images/product8.png',
                'price'      => 1.99,
                'rating'     => 5,
                'currencyId' => 1,
                'categoryId' => 0,
            ],
        ];

        $db = \Core\Services\Facades\App::make(DB::class);
//        $db->insert($products)->into('products')->execute();
//        return $db->getQuery();

        return $db->select(DB::ALL)->from('products')->fetch(\App\Models\Product::class);
    }
}
