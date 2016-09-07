<?php

use Truth\Support\Facades\Config;

require_once __DIR__ . '/System/helpers.php';
//require_once __DIR__ . '/System/Loader.php';

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container
$box = new \Truth\Support\Services\Locator\Box();
\Truth\Support\Services\Locator\Box::register($box);
\Truth\Support\Services\FileSystem\FS::register($box);
\Truth\Support\Services\Multilingual\Lang::register($box);
\Truth\Support\Services\Configuration\Config::register($box);
\Truth\Support\Services\View\Twig::register($box);
