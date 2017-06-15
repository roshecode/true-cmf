<?php
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);
//spl_autoload_register(function ($class) {
//    require __DIR__ . '/Pux/src/' . strtr($class, '\\', '/') . '.php';
//});
require __DIR__ . '/../vendor/nikic/fast-route/src/bootstrap.php';
require __DIR__ . '/../vendor/kint-php/kint/build/kint.php';
require __DIR__ . '/../libs/Truecode/Routing/RouteCollector.php';
require __DIR__ . '/../libs/Truecode/Routing/RouteCollection.php';
/*$options = [
    'dataGenerator' => 'FastRoute\\OldDataGenerator',
    'dispatcher' => 'FastRoute\\OldDispatcher',
];*/

const FIRST = 'first';
const MIDDLE = 'middle';
const LAST = 'last';
const UNKNOWN = 'unknown';
const TRUE_ROUTER_1 = 'true-route | routes: 300, matches: 30000, arguments: 1';
const TRUE_ROUTER_2 = 'true-route | routes: 300, matches: 30000, arguments: 9';
const FAST_ROUTER_1 = 'fast-route | routes: 300, matches: 30000, arguments: 1';
const FAST_ROUTER_2 = 'fast-route | routes: 300, matches: 30000, arguments: 9';

$stats = [
    TRUE_ROUTER_1 => [], FAST_ROUTER_1 => [],
    TRUE_ROUTER_2 => [], FAST_ROUTER_2 => []
];
$lastStr = null;

$options = [];
$nRoutes = 300;
$nMatches = 30000;

// MY ROUTER ===========================================================================================================

$truerouter = new Truecode\Routing\RouteCollector();
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $truerouter->match('GET', '' . $str . '/:arg', 'handler' . $i);
        $lastStr = $str;
    }

// first route ---------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/a/foo');
}
$stats[TRUE_ROUTER_1][FIRST] = microtime(true) - $startTime;

// middle route --------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/es/foo');
}
$stats[TRUE_ROUTER_1][MIDDLE] = microtime(true) - $startTime;

// last route ----------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/' . $lastStr . '/foo');
}
$stats[TRUE_ROUTER_1][LAST] = microtime(true) - $startTime;

// unknown route -------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/foobar/bar');
}
$stats[TRUE_ROUTER_1][UNKNOWN] = microtime(true) - $startTime;

// ---------------------------------------------------------------------------------------------------------------------

// FAST ROUTER =========================================================================================================

$fastrouter = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) use ($nRoutes, &$lastStr) {
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/{arg}', 'handler' . $i);
        $lastStr = $str;
    }
}, $options);

// first route ---------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/a/foo');
}
$stats[FAST_ROUTER_1][FIRST] = microtime(true) - $startTime;

// middle route --------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/es/foo');
}
$stats[FAST_ROUTER_1][MIDDLE] = microtime(true) - $startTime;

// last route ----------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/' . $lastStr . '/foo');
}
$stats[FAST_ROUTER_1][LAST] = microtime(true) - $startTime;

// unknown route -------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/foobar/bar');
}
$stats[FAST_ROUTER_1][UNKNOWN] = microtime(true) - $startTime;

// ---------------------------------------------------------------------------------------------------------------------

$nRoutes = 300;
$nArgs = 9;
$nMatches = 30000;
$args = implode('/', array_map(function($i) { return ':arg' . $i; }, range(1, $nArgs)));

// MY ROUTER ===========================================================================================================

$truerouter = new Truecode\Routing\RouteCollector();
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $truerouter->match('GET', '' . $str . '/' . $args, 'handler' . $i);
        $lastStr = $str;
    }

// first route ---------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/a/' . $args);
}
$stats[TRUE_ROUTER_2][FIRST] = microtime(true) - $startTime;

// middle route --------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/es/' . $args);
}
$stats[TRUE_ROUTER_2][MIDDLE] = microtime(true) - $startTime;

// last route ----------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/' . $lastStr . '/' . $args);
}
$stats[TRUE_ROUTER_2][LAST] = microtime(true) - $startTime;

// unknown route -------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $truerouter->make('GET', '/foobar/' . $args);
}
$stats[TRUE_ROUTER_2][UNKNOWN] = microtime(true) - $startTime;

//----------------------------------------------------------------------------------------------------------------------

// FAST ROUTER =========================================================================================================

$args = implode('/', array_map(function($i) { return "{arg$i}"; }, range(1, $nArgs)));

$fastrouter = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) use($nRoutes, $args, &$lastStr) {
    for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        $router->addRoute('GET', '/' . $str . '/' . $args, 'handler' . $i);
        $lastStr = $str;
    }
}, $options);

// first route ---------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/a/' . $args);
}
$stats[FAST_ROUTER_2][FIRST] = microtime(true) - $startTime;

// middle route --------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/es/' . $args);
}
$stats[FAST_ROUTER_2][MIDDLE] = microtime(true) - $startTime;

// last route ----------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/' . $lastStr . '/' . $args);
}
$stats[FAST_ROUTER_2][LAST] = microtime(true) - $startTime;

// unknown route -------------------------------------------------------------------------------------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $fastrouter->dispatch('GET', '/foobar/' . $args);
}
$stats[FAST_ROUTER_2][UNKNOWN] = microtime(true) - $startTime;

//----------------------------------------------------------------------------------------------------------------------

!d($stats);
