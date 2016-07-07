<?php

namespace Controllers;

use Data\Mail;
use Data\Session;
use Functions\Func;
use Models\ProductsModel;
use Tools\Functions;
use Views\FrontView;

class CartController extends BaseController {

  public function __construct() {
    $this->view = new FrontView();
  }

  private function initProductsFromCart() {
    $products = Array();
    $i = 0;
    foreach (Session::get('products') as $productId => $quantity) {
      $products[$i] = ProductsModel::getById($productId);
      $products[$i]['quantity'] = $quantity;
      $products[$i]['summary'] = $products[$i]['price'] * $quantity;
      ++$i;
    }
    return $products;
  }

  public function index() {
//    if ($this->view->products = $this->initProductsFromCart()) {
    $this->view->products = $this->initProductsFromCart();
      $this->view->display('cart');
//    } else {
//      throw new \Error;
//    }
  }

  public function order($data) {
    if (!empty($data[0]) && $data[0] == 'send') {
      echo Mail::send('Новый заказ!', Func::createProductsMailMsg($this->initProductsFromCart()));
    } else {
      $this->view->display('order');
    }
  }

  public function add($product_id) {
    $product_id = $product_id[0];
    $quantity = $_POST['quantity'];
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
    $this->view->display(Array(
      'productsText' => Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара'),
      'totalCost' => Session::get('totalCost') . '  грн.',
    ));
  }

  public function remove($product_id) {
    $product_id = $product_id[0];
    if ($product_id == 'all') {
      Session::clear();
      $this->view->display(Array(
        'productsCount' => 'x0',
        'productsText' => '0 товаров',
        'totalCost' => '0 грн.',
      ));
    } else {
      $products = Session::get('products');
      Session::sub('productsCount', $products[$product_id]);
      Session::sub('totalCost', $products[$product_id] * ProductsModel::getPrice($product_id));
      unset($_SESSION['products'][$product_id]);
      $this->view->display(Array(
        'productsCount' => 'x' . Session::get('productsCount'),
        'productsText' => Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара'),
        'totalCost' => Session::get('totalCost') . ' грн.',
      ));
    }
  }
}
