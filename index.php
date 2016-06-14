<?php

require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/bootstrap.inc';

\Data\Session::init([
  'products' => [],
  'productsCount' => 0,
  'totalCost' => 0
]);
//\Data\Session::destroy();
\Models\BaseModel::init();
\Views\BaseView::init(\External\Lib::twig());
\Route\Router::init();

//\Data\Transfer::import('test.csv', ['delimiter' => '&']);
//\Data\Transfer::addOrder();
