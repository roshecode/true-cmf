<?php

namespace View;

//abstract 
class BaseView {
  protected static $_template_dir = 'templates/';
  protected $data = [];
  protected $viewStructure = [];
  protected $currentViewStructure = [];
  protected $items = [];
  protected $item = [];
  protected $path = [];

  public function __set($name, $value) {
    $this->data[$name] = $value;
  }
  public function __get($name) {
    return $this->data[$name];
  }

  public function __construct($template_dir = null) {
    if ($template_dir != null) {
      $this->template_dir = $template_dir;
    }
  }

  public function currentView() {
    $viewStructure = $this->viewStructure;
    foreach ($this->path as $item) {
      $viewStructure = $viewStructure[$item];
    }
    return $viewStructure;
  }
  
  public function display($folder, $template) {
    ?><pre><?php
    echo $folder . ':' . $template . PHP_EOL;
//    print_r($this->currentViewStructure);
//    print_r($this->viewStructure);
    ?></pre><?php

    $this->path[] = $template;
    
    if (is_array($template)) {
      $this->item = $template;
      $template = $this->currentView();
    } else {
      if (array_key_exists($template, $this->currentViewStructure)) {
        $view = $this->currentView();
        if (is_array($view)) {
//        $items = array_keys($view);
//        $this->items = $items[0] ? $items : $view;
          $this->items = array_keys($view);
          $this->currentViewStructure = $view;
        } else {
          if ($view) {
            $this->items = [
              [
                'icon' => 'Kyivstar',
                'text' => '12345',
                'alias' => '12345'
              ],
              [
                'icon' => 'Kyivstar',
                'text' => '12345',
                'alias' => '12345'
              ]
            ];
          }
          array_pop($this->path);
        }
      }
    }
    
    include TEMPLATE . $folder . '/' . $template . '.php';
  }

  public function startRender($viewStructure) {
    $this->viewStructure = $viewStructure;
    $this->currentViewStructure = $viewStructure;
    $this->items = array_keys($viewStructure);
//    include TEMPLATE . '/main.php';
    include VIEW . 'html.php';
  }

  public function render2($template_file) {
    if (file_exists($this->template_dir.$template_file)) {
      include $this->template_dir . $template_file;
    } else {
      throw new \Exception('no template file ' . $template_file . ' present in directory ' . $this->template_dir);
    }
  }
}
