<?php
require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/bootstrap.inc';
//require_once __DIR__ . '/site/controllers/FrontController.php';

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

\Models\BaseModel::init();

$controllerName = '\\Controllers\\' . ucfirst($controllers[0]) . 'Controller';
$controller = new $controllerName;
$actionMethod = 'action' . ucfirst($view);
//$controller->$actionMethod($action);

$db = \Data\DB::getInstance();
$db->import('test.csv', ['delimiter' => '&']);
//include TEMPLATE . 'html.php';
