<?php

//declare(strict_types=1);

define('START_TIME', microtime(true));
define('BASEDIR', __DIR__);

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

(new \Dotenv\Dotenv(__DIR__))->load();

//$data = [
//    'name' => 'Rosem',
//    'login' => 'rosem'
//];
//
//function genTest($name) {
//    global $data;
//    var_dump($data);
//    $data['name'] = yield 'ROshe';
//}
//
//genTest('Roshe');
////var_dump($data);
//die;

//$arr = new \True\Support\Data\SoftArray([
//    'user' => [
//        'name' => 'Romanna',
//        'surname' => 'Semenyshyn',
//        'address' => [
//            'street' => 'Verbitskogo',
//            'city' => 'Ternopil',
//            'country' => 'Ukraine',
//            'info' => [
//
//            ]
//        ]
//    ],
//    'rating' => 100,
//]);
//
//$arr->set('user.address.country', 'Egypet');
//var_dump(
//    $arr->get('user.address.country', 'haha')
////    $arr['user']['address']['street']
//);
//
//die;

use Core\Services\Contracts\Kernel;
// init container and register services
$app = new \Core\Services\App(__DIR__ . '/config/services.php');
require_once BASEDIR . '/app/Routes/back/web.php';
require_once BASEDIR . '/app/Routes/back/api.php';
//$route = $app->make(\Core\Services\Route::class);
//$route->use('app/Routes/back' . $route->test('api/') ? 'api.php' : 'web.php');

//\Core\Services\Facades\Route::get('/:name', function ($name) {
//    echo $name;
//});

//$app->mutable('roshe', 'ROSHE');
//echo $app['roshe']; die;

//$app->mutable('mut', function($greet) {
//    return 'msg: ' . $greet;
//});
//echo $app->make('mut', ['Super']);
//echo $app->make('mut');
//echo $app->make('mut', ['Dup']);
//die;

//class Test
//{
//    public function __invoke(\Core\Services\DB $db)
//    {
//        var_dump($db);
//    }
//}
//$app->bind('test', Test::class);
//var_dump(new ReflectionFunction($app->make('test'))); die;
//
//$app->bind('test', function(\Core\Services\Contracts\DB $db) {
//    var_dump($db->select('*')->from('products')->fetch(\App\Models\Product::class));
//});
//$app->make('test'); //die;

// launch app
//$kernel = $box[KernelInterface::class];
$kernel = $app->make(Kernel::class);
//var_dump($kernel); die;
//$request = T\Services\Request::capture();
$request = $app->make(\Psr\Http\Message\ServerRequestInterface::class);
//$box->instance(\T\Interfaces\Request::class, $request);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

//$box->make(function () {});

//$server = Zend\Diactoros\Server::createServer(
//    new RequestHandler(),
//    $_SERVER,
//    $_GET,
//    $_POST,
//    $_COOKIE,
//    $_FILES
//);
//
//$server->listen(function ($request, $response, $error = null) {
//    // Final Handler
//    if (! $error) {
//        return $response;
//    }
//
//    // Handle extra errors etc here
//});

//echo '<hr />';
//echo 'END TIME: ' . (microtime(true) - START_TIME) * 1000;
