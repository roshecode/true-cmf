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
$filter = new Twig_SimpleFilter('correctEnd', function($val, $zero, $one, $two) {
  echo \Tools\Functions::correctEnd($val, $zero, $one, $two);
});
$twig->addFilter($filter);

//$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/' . DOMAIN, '', $_SERVER['REQUEST_URI']);
$controllers = explode('/', $url);
array_shift($controllers);
$action = array_pop($controllers);
$view = array_pop($controllers);

if (empty($controllers)) {
  $controllers = 'front';
} else {
  $controllers = $controllers[0];
}
if (!$view) {
  $view = 'page';
}
if (!$action) {
  $action = 0;
}
//\Tools\Functions::printr($controllers);
//\Tools\Functions::printr($view);
//\Tools\Functions::printr($action);

$session = \Data\Session::employ([
  'products' => [],
  'productsCount' => 0,
  'totalCost' => 0
]);
//\Tools\Functions::printr($_SESSION);
//\Data\Session::destroy();
\Models\BaseModel::init();

$controllerName = '\\Controllers\\' . ucfirst($controllers) . 'Controller';
$controller = new $controllerName($twig);
$actionMethod = 'act' . ucfirst(strtolower($view));
$controller->$actionMethod($action);

//\Data\Transfer::import('test.csv', ['delimiter' => '&']);
