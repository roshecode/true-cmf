<?php
require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/bootstrap.inc';
//require_once __DIR__ . '/site/controllers/FrontController.php';

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(SITE . '/data/templates');
$twig = new Twig_Environment($loader, array(
  'cache' => SITE . '/compilation_cache',
  'auto_reload' => true
));

$twig->render('test.php.twig', ['text' => 'HEllo world!!!']);

$url = str_replace('/' . DOMAIN . '/', '', $_SERVER['REQUEST_URI']);
$controllers = explode('/', $url);
$action = array_pop($controllers);
$view = array_pop($controllers);

//print_r($controllers);
//print_r($view);
//print_r($action);

if (empty($controllers)) {
  $controllers[0] = 'home';
//  if (!$controllers[0]) {
//    $controllers[0] = 'home';
//  }
}
if (!$view) {
  $view = 'page';
}
if (!$action) {
  $action = 'home';
}

$session = \Data\Session::getInstance();
\Models\BaseModel::init();

$controllerName = '\\Controllers\\' . ucfirst($controllers[0]) . 'Controller';
$controller = new $controllerName($twig);
$actionMethod = 'action' . ucfirst($view);
$controller->$actionMethod($action);

//\Data\Transfer::import('test.csv', ['delimiter' => '&']);
//include TEMPLATE . '/html.php';
