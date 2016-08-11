<?php

namespace Views;

class BaseView implements \Iterator {
  /**
   * @var \Twig_Environment
   */
  protected static $tplParser;
  protected $path = '';
//  protected $data = [];
  public $vars = Array();

  public static function init($tplParser) {
    static::$tplParser = $tplParser;
  }

  public function __construct() {
    $this->vars['session'] = $_SESSION;
    $this->vars['title'] = TITLE;
    $this->vars['home'] = HOME;
    $data = explode('/', HOME, 2);
    $this->vars['data'] = $data[0] . '/' . DATA;
  }

  public function __set($name, $value) {
    $this->vars[$name] = $value;
  }
  public function __get($name) {
    return $this->vars[$name];
  }

  protected function ajax() {
    return (isset($_GET['ajax']) && $_GET['ajax']) || (isset($_POST['ajax']) && $_POST['ajax']);
  }

  public function display($template)
  {
    if ($this->ajax()) {
      // ajax: return only needed part
      if (is_array($template)) {
        echo json_encode($template);
        return;
      }
    } else {
      $this->vars['content'] = $this->path . '/' . $template . '.php.twig';
      $template = 'main';
    }
    $th = static::$tplParser->loadTemplate($this->path . '/' . $template . '.php.twig');
    $th->display($this->vars);
  }
  
  public function render($template) {
    $th = static::$tplParser->loadTemplate($this->path . '/' . $template . '.php.twig');
    return $th->render($this->vars);
  }

  public function current()
  {
    return current($this->vars);
  }
  public function next()
  {
    return next($this->vars);
  }
  public function key()
  {
    return key($this->vars);
  }
  public function valid()
  {
    $key = key($this->vars);
    return $key !== NULL && $key !== FALSE;
  }
  public function rewind()
  {
    reset($this->vars);
  }
}
