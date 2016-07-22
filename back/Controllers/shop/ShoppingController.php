<?php

namespace Backend\Shop;

use True\Controllers\BaseController;

class ShoppingController extends BaseController{
    public function product($name) {
        echo 'PRODUCT PAGE: '.$name[0];
    }
}
