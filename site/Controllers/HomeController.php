<?php

namespace Controllers;

use Models\CategoriesModel;
use Models\MenuModel;
use Models\PageModel;
use Models\ProductsModel;
use Views\FooterView;
use Views\HeaderView;
use Views\MenuView;
use Views\PageView;
use Views\ProductsView;
use Views\SidebarView;

class HomeController extends BaseController {
  protected $templateParser;

  public function __construct($templateParser) {
    $this->templateParser = $templateParser;
  }

  public function actionPage($page) {
    $session = \Data\Session::getInstance();
//    $this->templateParser->render('sidebar.php.twig', CategoriesModel::getAll());
    $page['home'] = HOME;
    $page['data'] = DATA;
    $page = PageModel::get($page);
    
    echo $this->templateParser->render('menu.php.twig', ['items' => MenuModel::getAll()]);
    echo $this->templateParser->render('page.php.twig', $page);
//    HeaderView::display();
//    MenuView::display(MenuModel::getAll());
//    SidebarView::display(CategoriesModel::getAll());
//    PageView::display(PageModel::get($page));
//    FooterView::display(MenuModel::getAll());
  }

  public function actionCategory($category) {
    $session = \Data\Session::getInstance();
    HeaderView::display();
    MenuView::display(MenuModel::getAll());
    SidebarView::display(CategoriesModel::getAll());
//    echo 'ТОВАР ' . $category;
    ProductsView::display(ProductsModel::get($category));
    FooterView::display(MenuModel::getAll());
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
