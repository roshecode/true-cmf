<?php

use Truth\Support\Facades\Config;

require_once __DIR__ . '/System/helpers.php';
//require_once __DIR__ . '/System/Loader.php';

// set errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

// init container and register
$box = new \Truth\Support\Services\Locator\Box();
$register = new \Truth\Support\Services\Registry\Register($box);
