<?php

namespace Views;

use Models\CategoriesModel;
use Models\MenuModel;

class FrontView extends BaseView {
  protected $template = 'main.php';
  
  public function __construct() {
    parent::__construct();
    $this->menu_links = MenuModel::getAll();
    $this->categories = CategoriesModel::getAll();
  }
}
