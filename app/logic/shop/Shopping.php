<?php

namespace Logic\Shop;

use Truth\Controllers\Controller;

class Shopping extends Controller{
    public function product($name) {
        echo 'PRODUCT PAGE: '.$name[0];
    }
}
