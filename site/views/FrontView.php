<?php

namespace Views;

use Models\CategoriesModel;
use Models\MenuModel;

class FrontView extends BaseView {
  protected $template = 'main.php';
  
  public function __construct() {
    parent::__construct();
    $this->title = "Главная страница | avtomagazin.dp.ua";
    $this->menu_links = MenuModel::getAll();
    $this->categories = CategoriesModel::getAll();
  }

  public function error() {
    $this->vars['content'] = 'error.php.twig';
    $th = static::$tplParser->loadTemplate('main.php.twig');
    $th->display($this->vars);
  }
}
