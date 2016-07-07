<?php

namespace Controllers;

use Data\Mail;
use Data\Session;
use Functions\Func;
use Models\CategoriesModel;
use Models\MenuModel;
use Models\PageModel;
use Models\ProductsModel;
use Tools\Functions;
use Views\FrontView;

class HomeController extends BaseController {
  
  public function __construct() {
    $this->view = new FrontView();
  }

  public function index() {
    if ($this->view->page = PageModel::getById(0)) {
      $this->view->display('home-page');
    } else {
      throw new \Exception('404');
    }
  }

  public function page($page_alias) {
    $page_alias = $page_alias[0];
    if ($this->view->page = PageModel::getById(MenuModel::getIdByAlias($page_alias))) {
//      echo $page_alias;
      $this->view->display('page');
    } else {
      throw new \Exception('404');
    }
  }

  public function product($data) {
    $step = 50;

    $category = $data[0];
    if (isset($data[1])) {
      $page = $data[1];
    } else {
      $page = 1;
    }
//    $this->view->products = ProductsModel::getByCategory($category);
    if ($this->view->products = ProductsModel::getByCategoryRange($category, ($page - 1) * $step, $step)) {
      $tmp = CategoriesModel::getById($data[0]);
//      Functions::printr($tmp);
      $this->view->category = $tmp;
//      $this->view->page = $page;
      $this->view->currentPage = $page;
      $this->view->count = intval(ProductsModel::countByCategory($category) / $step);
//      $this->view->count = intval(count($this->view->products) / $step);
      $this->view->display('products');
    } else {
      throw new \Exception('404');
    }
  }

  public function search() {
    $step = 50;

//    if (isset($data[1])) {
//      $page = $data[1];
//    } else {
//      $page = 1;
//    }
//    $this->view->products = ProductsModel::getByCode(Functions::leaveLettersAndNumbers($_GET['code']));
//    if ($this->view->products = ProductsModel::getByCodeLike(Functions::leaveLettersAndNumbers($_GET['code']))) {
//    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page = $_GET['page'];

    if ($this->view->products = ProductsModel::getLikeRange($_GET['search'], ($page - 1) * $step, $step)) {
//      if (!isset($_SESSION['search'])) $_SESSION['search'] = $_GET['search'];
      $this->view->search = $_GET['search'];
      $this->view->currentPage = $page;
      $this->view->count = intval(ProductsModel::countSearch($_GET['search']) / $step);
      $this->view->display('search');
    } else {
      throw new \Exception('404');
    }
  }
  
  public function form($data) {
    if (!empty($data[1]) && $data[1] == 'send') {
        switch ($data[0]) {
          case 'callback': echo Mail::send('Перезвоните мне!', Func::createCallbackMailMessage());
            break;
          case 'vin': echo Mail::send('Новый VIN-запрос!', Func::createVinMailMsg());
            break;
          default: throw new \Exception('404');
        }
    } else {
      $this->view->display($data[0]);
    }
  }
  
  public function admin() {
    $admin = new AdminController();
    $admin->index();
  }
}
