<?php

use Truth\Support\Facades\Config;

require_once __DIR__ . '/System/helpers.php';
//require_once __DIR__ . '/System/Loader.php';

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container
new \Truth\IoC\Box();
\Truth\Support\Services\Box::register();
\Truth\Support\Services\Multilingual\Lang::register();
\Truth\Support\Services\Configuration\Config::register();
\Truth\Support\Services\View\Twig::register();
