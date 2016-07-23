<?php

namespace Logic\Shop;

use True\Controllers\Controller;

class Shopping extends Controller{
    public function product($name) {
        echo 'PRODUCT PAGE: '.$name[0];
    }
}
