<?php

namespace Controllers;

class BaseController {
  /**
   * @var \Views\BaseView
   */
  protected $view;
//  protected $current_action;

  public function __construct() {
//    $this->current_action = $current_action;
  }

  public function index() {
    //
  }
}
