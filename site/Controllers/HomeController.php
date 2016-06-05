<?php

namespace Controllers;

use Models\CategoriesModel;
use Models\MenuModel;
use Models\PageModel;
use Views\HeaderView;
use Views\MenuView;
use Views\PageView;
use Views\SidebarView;

class HomeController extends BaseController {
  public function actionPage($page) {
    HeaderView::display();
    MenuView::display(MenuModel::getAll());
    SidebarView::display(CategoriesModel::getAll());
    PageView::display(PageModel::get($page));
  }

  public function actionCategory($category) {
    HeaderView::display();
    MenuView::display(MenuModel::getAll());
    SidebarView::display(CategoriesModel::getAll());
    echo 'ТОВАР ' . $category;
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
