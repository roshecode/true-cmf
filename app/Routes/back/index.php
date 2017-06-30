<?php

use T\Facades\Route;

//Route::get('::.*', function() {
//    return '<!doctype html>
//<html lang="en">
//<head>
//    <meta charset="UTF-8">
//    <meta name="viewport" content="width=device-width, initial-scale=1.0">
//    <meta name="viewport"
//          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
//    <meta http-equiv="X-UA-Compatible" content="ie=edge">
//    <title>Home page</title>
//    <link rel="stylesheet" href="/css/style.css">
//    <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
//</head>
//<body>
//<div id="app"></div>
//<noscript>
//    <p>To use <mark>True Framework</mark>, please enable JavaScript.</p>
//</noscript>
//<script src="/js/main.js"></script>
//</body>
//</html>';
//});

Route::get('::.*', function() {
    return $this->box[T\Services\View::class]->render('default/home', ['title' => 'Home']);
});

Route::get('/admin', function() {
    return $this->box[T\Services\View::class]->render('default/admin', ['title' => 'Admin']);
});

include __DIR__ . '/api/api.php';
