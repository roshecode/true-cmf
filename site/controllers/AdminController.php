<?php

namespace Controllers;

use Data\Session;
use Data\Transfer;
use Models\PageModel;
use Models\UsersModel;
use Tools\Functions;
use Views\AdminView;

class AdminController extends BaseController {
  
  public function __construct() {
    $this->view = new AdminView();
  }

  public function isAdmin() {
    $user = UsersModel::getById(1);
    $password = $user['password'];
    if (isset($_SESSION['password']) && $_SESSION['password'] == $password) {
      //
    } else header('Location: /admin');
  }

  public function index() {
//    $password = md5('admin');
//      $this->view->pages = PageModel::getAll();
//      $this->view->display('page');
      if (isset($_POST['password'])) {
        $_SESSION['password'] = $_POST['password'];
//        $this->page(0);
        header('Location: /admin/page/0');
      } else {
        echo $this->view->render('login');
      }
  }
  
  public function page($id) {
    $this->isAdmin();
    $id = $id[0];
//    $this->view->page = PageModel::getById($id);
    $this->view->pages = PageModel::getAll();
    $this->view->page = $this->view->pages[$id];
    $this->view->display('page');
  }

  public function import() {
    $this->isAdmin();
    $this->view->pages = PageModel::getAll();
    $this->view->display('import');
  }

  public function importprice() {
    $this->isAdmin();
//    echo 'GOOD';
//    $file = file_get_contents($_FILES['file']['tmp_name']);
//    Functions::printr($file);

//    if (!empty($FILES['file'])) {
//      $file = file_get_contents($_FILES['file']['tmp_name']);
      Transfer::import($_FILES['file']['tmp_name'], Array('delimiter' => $_POST['delimiter'], 'category' => $_POST['category']));
//    }
  }

  public function settings() {
    $this->isAdmin();
    $this->view->pages = PageModel::getAll();
    $admin = UsersModel::getById(1);
    $this->view->email = $admin['email'];
    $this->view->password = $admin['password'];
    $this->view->display('settings');
  }
  
  public function save($item) {
    $this->isAdmin();
//    Functions::printr($_POST['editor']);
    switch($item[0]) {
      case 'page':
        if (isset($_POST['editor'])) {
//          $page['id'] = $item[1];
          $page['text'] = stripslashes($_POST['editor']);
          $page['title'] = stripslashes($_POST['title']);
//          if (PageModel::save($page, 'text', 'text')) {
          if (PageModel::update('id', $item[1], $page)) {
//          if (PageModel::upd($item[1], $page)) {
//            $this->view->page = PageModel::getById([$item[1]]);
//            $this->view->display('page');
            header('Location: /admin/page/' . $item[1]);
            exit;
          }
        }
      break;
      case 'settings':
        if (isset($_POST['email']) && isset($_POST['password'])) {
          UsersModel::update('id', 1, Array('email' => $_POST['email'], 'password' => $_POST['password']));
          echo 'Данные успешно сохранены';
        }
      break;
    }
  }

  public function logout() {
    Session::destroy();
    header('Location: /'); exit;
  }

}
