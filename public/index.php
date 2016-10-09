<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

$start = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use T\Facades\Box;
use T\Facades\Lang;
use T\Facades\Config;
use T\Facades\Route;
use T\Facades\View;

View::addLayout('layouts/home', ['logo' => 'static', 'article' => 'table']);

Route::get('/', function() {
    View::render('layouts/home', [
        'article' => [
            'content' => [
                ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
                ['title' => 'My second article', 'text' => 'I like what I doing.'],
                ['title' => 'My third article', 'text' => 'I hate what I doing.'],
                ['title' => 'LAST article', 'text' => 'Dog eats cat!!!'],
                ['title' => 'LAST article', 'text' => 'Dog eats cat!!!'],
                ['title' => 'LAST article', 'text' => 'Dog eats cat!!!'],
                ['title' => 'LAST article', 'text' => 'Dog eats dos!!!'],
            ],
            'info' => ['page' => 1, 'columns' => 3]
        ]
    ]);
});
Route::delete('delete', function() {
    echo 'and DELETE method';
});
Route::put('put', function() {
    echo 'PUT method';
    View::render('blocks/logo');
});

?>
    <form action="/put" method="PUT">
        <input type="submit" />
    </form>
    <form action="/delete" method="DELETE">
        <input type="submit" />
    </form>

    <script   src="http://code.jquery.com/jquery-3.1.1.js"   integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="   crossorigin="anonymous"></script>

    <script>
        var hiddenMethod = $('<input type="hidden" name="_method">');
        function addRequestMethod(item) {
            item.append(hiddenMethod.clone().attr('value', item.attr('method')));
            item.attr('method', 'POST');
        }
        $('form:not([method=GET]):not([method=POST])').each(function(index, item) {
            addRequestMethod($(item));
        });
    </script>
<?php

print_r((microtime(true) - $start) * 1000 . 'ms');
