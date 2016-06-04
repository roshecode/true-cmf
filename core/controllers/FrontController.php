<?php

$url = str_replace(DOMAIN . '/', '', $_SERVER['REQUEST_URI']);
$actions = explode('/', $url);
$view = array_pop($actions);

//print_r($actions);
//print_r($view);

$view = new \View\BaseView(TEMPLATE);
$viewPage = [
  'header' => [
    'brand' => null,
    'callback' => null,
//    'phones' => 'phone_link',
    'cart' => null,
    'user' => null,
    'menu' => 'menu_link'
  ],
  'sidebar' => [
    'search' => null
  ],
  'content' => null,
];
$view->startRender($viewPage);

//require_once MODEL . '/categoriesModel.php';
//require_once VIEW . '/layout/html.php';

//$categories = new \Model\CategoriesModel();

//$templates = new \View\BaseView('site/theme/default/templates/');
//$templates->items = [
//  [
//    'icon' => 'home',
//    'text' => 'О компании',
//    'reference' => '/about'
//  ],
//  [
//    'icon' => 'money',
//    'text' => 'Доставка и оплата',
//    'reference' => '/deliveryandpayment'
//  ]
//];
//$templates->render('regions/block_menu.php');

?><pre><?php
//print_r($categories->get(1)-);
//echo $categories->get(1)->name;
?></pre>