<?php
error_reporting(E_ALL);
require __DIR__ . '/../app/vendor/nikic/fast-route/src/bootstrap.php';
//spl_autoload_register(function ($class) {
//    require __DIR__ . '/Pux/src/' . strtr($class, '\\', '/') . '.php';
//});
$DIR = __DIR__ . '/../app/core/Support/Services/Routing';
require $DIR . '/TrueRouter/Router.php';
require $DIR . '/Router.php';
require $DIR . '/../Http/Response.php';
function callback() {}
/*$options = [
    'dataGenerator' => 'FastRoute\\OldDataGenerator',
    'dispatcher' => 'FastRoute\\OldDispatcher',
];*/
$options = [];
$nRoutes = 200;
$nMatches = 30000;

// MY ROUTER ----------------------------------------------------------

//$router = new \Truth\Support\Services\Routing\TrueRouter\Router('');
$router = new \Truth\Support\Services\Routing\Router();
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->match('GET', '' . $str . '/:arg', 'handler' . $i);
        $lastStr = $str;
    }
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->make('GET', 'a/foo');
}
printf("TrueRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->make('GET', '' . $lastStr . '/foo');
}
printf("TrueRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->make('GET', 'foobar/bar');
}
printf("TrueRoute unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// -------------------------------------------------------------------

echo '<br />';

// FAST ROUTER --------------------------------------------------------

$router = FastRoute\simpleDispatcher(function($router) use ($nRoutes, &$lastStr) {
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/{arg}', 'handler' . $i);
        $lastStr = $str;
    }
}, $options);
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/a/foo');
}
printf("FastRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/' . $lastStr . '/foo');
}
printf("FastRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/foobar/bar');
}
printf("FastRoute unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// ---------------------------------------------------------

echo '<br /><br />';

// MY ROUTER ------------------------------------------------------------------

$nRoutes = 200;
$nArgs = 9;
$nMatches = 20000;
$args = implode('/', array_map(function($i) { return ':arg' . $i; }, range(1, $nArgs)));

//$router = new \Truth\Support\Services\Routing\TrueRouter\Router('');
$router = new \Truth\Support\Services\Routing\Router();
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->match('GET', '' . $str . '/' . $args, 'handler' . $i);
        $lastStr = $str;
    }
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->make('GET', 'a/' . $args);
}
printf("TrueRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->make('GET', '' . $lastStr . '/' . $args);
}
printf("TrueRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->make('GET', 'foobar/' . $args);
}
printf("TrueRoute unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);

echo '<br />';

// FAST ROUTER ----------------------------------------------------------------

//$nRoutes = 100;
//$nArgs = 9;
//$nMatches = 20000;
$args = implode('/', array_map(function($i) { return "{arg$i}"; }, range(1, $nArgs)));
//$args = implode('/', array_map(function($i) { return ':arg' . $i; }, range(1, $nArgs)));
$router = FastRoute\simpleDispatcher(function($router) use($nRoutes, $args, &$lastStr) {
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/' . $args, 'handler' . $i);
        $lastStr = $str;
    }
}, $options);
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/a/' . $args);
}
printf("FastRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// last route

$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/' . $lastStr . '/' . $args);
}
printf("FastRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/foobar/' . $args);
}
printf("FastRoute unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);
