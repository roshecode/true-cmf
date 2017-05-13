<?php
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);
require __DIR__ . '/../vendor/nikic/fast-route/src/bootstrap.php';
//spl_autoload_register(function ($class) {
//    require __DIR__ . '/Pux/src/' . strtr($class, '\\', '/') . '.php';
//});
$DIR = __DIR__ . '/../core/Services/Routing';
//require $DIR . '/TrueRouter/Router.php';
require $DIR . '/Route.php';
function callback() {}
/*$options = [
    'dataGenerator' => 'FastRoute\\OldDataGenerator',
    'dispatcher' => 'FastRoute\\OldDispatcher',
];*/
$options = [];
$nRoutes = 200;
$nMatches = 30000;
$lastStr = null;


// MY ROUTER ----------------------------------------------------------

//$router = new \Truth\Support\Services\Routing\TrueRouter\Router('');
$myrouter = new \T\Services\Routing\Route();
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $myrouter->match('GET', '' . $str . '/:arg', 'handler' . $i);
        $lastStr = $str;
    }
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $myrouter->make('GET', 'a/foo');
}
printf("TrueRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $myrouter->make('GET', '' . $lastStr . '/foo');
}
printf("TrueRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $myrouter->make('GET', 'foobar/bar');
}
printf("TrueRoute unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// -------------------------------------------------------------------

echo '<br />';

// FAST ROUTER --------------------------------------------------------

$fastrouter = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) use ($nRoutes, &$lastStr) {
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/{arg}', 'handler' . $i);
        $lastStr = $str;
    }
}, $options);
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/a/foo');
}
printf("FastRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/' . $lastStr . '/foo');
}
printf("FastRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/foobar/bar');
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
$myrouter = new \T\Services\Routing\Route();
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $myrouter->match('GET', '' . $str . '/' . $args, 'handler' . $i);
        $lastStr = $str;
    }
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $myrouter->make('GET', 'a/' . $args);
}
printf("TrueRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $myrouter->make('GET', '' . $lastStr . '/' . $args);
}
printf("TrueRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $myrouter->make('GET', 'foobar/' . $args);
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
$fastrouter = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) use($nRoutes, $args, &$lastStr) {
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/' . $args, 'handler' . $i);
        $lastStr = $str;
    }
}, $options);
// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/a/' . $args);
}
printf("FastRoute first route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// last route

$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/' . $lastStr . '/' . $args);
}
printf("FastRoute last route: %f\n", microtime(true) - $startTime);
//var_dump($res);
// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/foobar/' . $args);
}
printf("FastRoute unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);
