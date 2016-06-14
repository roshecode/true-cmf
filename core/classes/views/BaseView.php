<?php

namespace Views;

class BaseView implements \Iterator {
  /**
   * @var \Twig_Environment
   */
  protected static $tplParser;
  protected $template = 'unknown';
//  protected $data = [];
  public $vars = [];

  public static function init($tplParser) {
    static::$tplParser = $tplParser;
  }

  public function __construct() {
    $this->vars['session'] = $_SESSION;
    $this->vars['title'] = TITLE;
    $this->vars['home'] = HOME;
    $this->vars['data'] = explode('/', HOME, 2)[0] . '/' . DATA;
  }

  public function __set($name, $value) {
    $this->vars[$name] = $value;
  }
  public function __get($name) {
    echo $name;
    return $this->vars[$name];
  }

  public function display($template)
  {
//    print_r($template);
    if (isset($_GET['ajax']) && $_GET['ajax']) {
      // ajax: return only needed part
      if (is_array($template)) {
        echo json_encode($template);
        return;
      }
    } else {
      $this->vars['content'] = $template . '.php.twig';
      $template = 'main';
    }
    $th = static::$tplParser->loadTemplate($template . '.php.twig');
    $th->display($this->vars);
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
