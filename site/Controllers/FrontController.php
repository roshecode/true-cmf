<?php

namespace Controllers;

use Data\Session;
use Models\CategoriesModel;
use Models\MenuModel;
use Models\PageModel;
use Models\ProductsModel;
use Tools\Functions;
use Views\FooterView;
use Views\HeaderView;
use Views\MenuView;
use Views\PageView;
use Views\ProductsView;
use Views\SidebarView;

class FrontController extends BaseController {
  protected $templateParser;
  protected $data;

  public function __construct($templateParser) {
    global $session;
//    $this->data['session'] = &$session;
    $this->data['session'] = $_SESSION;
//    $this->data['session']->products = [];
    $this->templateParser = $templateParser;
    $this->data['home'] = HOME;
    $this->data['data'] = DATA;
    $this->data['menu_links'] = MenuModel::getAll();
    $this->data['categories'] = CategoriesModel::getAll();
  }

  protected function display($template, $vars) {
    if (isset($_GET['part']) && $_GET['part']) {
//      $this->templateParser->loadTemplate('main_part.php.twig');
    } else {
//      $this->templateParser->loadTemplate('main.php.twig');
      $vars['content'] = $template . '.php.twig';
      $template = 'main';
    }
    $th = $this->templateParser->loadTemplate($template . '.php.twig');
    $th->display($vars);
  }

  public function actPage($page) {
    if ($page > 0) $this->data['menu_links'][$page - 1]['active'] = true;
    $this->data['page'] = PageModel::get($page);
//    echo $this->templateParser->render('page.php.twig', $this->data);
    $this->display('page', $this->data);
  }

  public function actProduct($category) {
    $this->data['categories'][CategoriesModel::getOrderWithId($category) - 1]['active'] = true;
//    $this->data['categories'][$_GET['i']]['active'] = true;
//    $this->data['categories'][$_POST['i']]['active'] = true;
//    $category_id = CategoriesModel::getIdWithOrder($category);
    $this->data['products'] = ProductsModel::getWithCategory($category);
//    echo $this->templateParser->render('products.php.twig', $this->data);
    $this->display('products', $this->data);
  }

  public function actAddToCart($product_id) {
//    $products = Session::getReference('products');
//    $fh = fopen('log.txt', 'a+'); fwrite($fh, $product_id . ': ' . $_GET['quantity'] . PHP_EOL);
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
    $response['productsCount'] = Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара');
    $response['totalCost'] = Session::get('totalCost') . '  грн.';
    echo json_encode($response);
  }

  public function actSearch($code) {
//    $this->data['products'] = ProductsModel::getWithCode(Functions::leaveLettersAndNumbers($code));
    $this->data['products'] = ProductsModel::getWithCode(Functions::leaveLettersAndNumbers($_GET['code']));
//    echo $this->templateParser->render('search.php.twig', $this->data);
    $this->display('products', $this->data);
  }
  
  public function actCart() {
    $products = [];
    $i = 0;
    foreach (Session::get('products') as $productId => $quantity) {
      $products[$i] = ProductsModel::get($productId);
      $products[$i]['quantity'] = $quantity;
      $products[$i]['summary'] = $products[$i]['price'] * $quantity;
      ++$i;
    }
    $this->data['cart'] = $products;
    $this->data['session'] = Session::getAll();
    $this->display('cart', $this->data);
  }

  public function actRemove($product_id) {
    if ($product_id == 'all') {
      Session::put('products', []);
      Session::put('productsCount', 0);
      Session::put('totalCost', 0);
      $response['countAlone'] = 'x0';
      $response['productsCount'] = 0 . ' товаров';
      $response['totalCost'] = 0 . ' грн.';
      echo json_encode($response);
    } else {
      $_SESSION['productsCount'] -= $_SESSION['products'][$product_id];
      $totalCost = $_SESSION['products'][$product_id] * ProductsModel::getPrice($product_id);
      Session::sub('totalCost', $totalCost);
      unset($_SESSION['products'][$product_id]);
      $response['productsCount'] = Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара');
      $response['countAlone'] = 'x' . Session::get('productsCount');
      $response['totalCost'] = Session::get('totalCost') . '  грн.';
      echo json_encode($response);
    }
  }

  public function actOrder() {
    echo 'Coming soon..';
  }

  public function actCallback($send) {
    if ($send == 'send') {
//      echo $_POST['name'];
//      echo $_POST['phone'];
      echo 'success';
    } else {
      $this->display('callback', $this->data);
    }
  }

  public function actError() {
    $this->display('error', $this->data);
  }
}

//$view = new \Views\BaseView(TEMPLATE);
//$viewPage = [
//  'header' => [
//    'brand' => null,
//    'callback' => null,
//    'phones' => 'phone_link',
//    'cart' => null,
//    'user' => null,
//    'menu' => 'menu_link'
//  ],
//  'sidebar' => [
////    'search' => null,
//    'categories' => 'category_link'
//  ],
//  'content' => null,
//];
//$view->startRender($viewPage);
