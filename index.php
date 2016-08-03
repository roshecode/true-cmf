<?php

use True\Routing\Router;

require_once __DIR__ . '/core/bootstrap.inc';

Router::start();
Router::get('', function($data) {
    echo $data;
});

