<?php

namespace Controllers;

use Data\Session;
use Models\MenuModel;
use Models\PageModel;
use Models\ProductsModel;
use Tools\Functions;
use Views\FrontView;

class FrontController extends BaseController {
  
  public function __construct() {
    $this->view = new FrontView();
  }

//  private function tryDisplay

  public function page($page_alias) {
    if ($page_alias) {
      $this->view->page = PageModel::getById(MenuModel::getIdByAlias($page_alias));
    } else {
      $this->view->page = PageModel::getById($page_alias);
    }
//    if ($this->view->page && is_numeric(BaseView::$vars['page'])) {
      $this->view->display('page');
  }

  public function product($category) {
    $this->view->products = ProductsModel::getByCategory($category);
    $this->view->display('products');
  }

  public function search($code) {
    $this->view->products = ProductsModel::getByCode(Functions::leaveLettersAndNumbers($_GET['code']));
    $this->view->display('products');
  }
  
  public function cart() {
    $products = [];
    $i = 0;
    foreach (Session::get('products') as $productId => $quantity) {
      $products[$i] = ProductsModel::getById($productId);
      $products[$i]['quantity'] = $quantity;
      $products[$i]['summary'] = $products[$i]['price'] * $quantity;
      ++$i;
    }
    $this->view->products = $products;
    $this->view->display('cart');
  }

  public function add($product_id) {
    $quantity = $_GET['quantity'];
    if ($quantity < 0) $quantity = 0;
    $products = Session::get('products');
    if (isset($products[$product_id])) {
      $products[$product_id] += $quantity;
    } else {
      $products[$product_id] = $quantity;
    }
    Session::put('products', $products);
    Session::put('productsCount', Session::countValues('products'));
    Session::add('totalCost', $quantity * ProductsModel::getPrice($product_id));
    $this->view->display([
      'productsText' => Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара'),
      'totalCost' => Session::get('totalCost') . '  грн.',
    ]);
  }

  public function remove($product_id) {
    if ($product_id == 'all') {
      Session::clear();
      $this->view->display([
        'productsCount' => 'x0',
        'productsText' => '0 товаров',
        'totalCost' => '0 грн.',
      ]);
    } else {
      Session::sub('productsCount', Session::get('products')[$product_id]);
      $totalCost = Session::get('products')[$product_id] * ProductsModel::getPrice($product_id);
      Session::sub('totalCost', $totalCost);
      unset(Session::get('products')[$product_id]);
      $this->view->display([
        'productsCount' => 'x' . Session::get('productsCount'),
        'productsText' => Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара'),
        'totalCost' => Session::get('totalCost') . ' грн.',
      ]);
    }
  }

  public function order() {
    echo 'Coming soon..';
  }

  public function callback($send) {
    if ($send == 'send') {
      echo 'success';
    } else {
//      echo $_POST['name'];
//      echo $_POST['phone'];
      $this->view->display('callback');
    }
  }
}
