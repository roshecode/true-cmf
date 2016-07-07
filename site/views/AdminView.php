<?php

namespace Views;

use Models\CategoriesModel;
use Models\MenuModel;

class AdminView extends BaseView {
  protected $path = 'admin';

  public function __construct() {
    parent::__construct();
//    $this->menu_links = MenuModel::getAll();
//    $this->categories = CategoriesModel::getAll();
  }
}
